const express = require("express");
const router = express.Router();

const wrapAsync = require("../utils/wrapAsync");
const passport = require("passport");
const { saveRedirectUrl } = require("../middleware.js");
const UserController = require("../controllers/users.js");

router.route("/signup")
    .get(
        UserController.renderSignupForm 
    )
    .post(
        saveRedirectUrl,
        wrapAsync(UserController.registerUser)
    );

router.route("/login")
    .get( 
        UserController.renderLoginForm
    )
    .post(
        saveRedirectUrl,
        passport.authenticate("local", {
            failureRedirect: "/login",
            failureFlash : true
        }) , 
        wrapAsync(UserController.LoginUser)
    );


router.get("/logout",
    UserController.LogoutUser
);

module.exports = router;