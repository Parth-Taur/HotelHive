const User = require("../models/user.js");

module.exports.renderSignupForm = (req,res) => {
    res.render("users/signup.ejs");
}

module.exports.registerUser = async(req,res) => {
    try {
        let newUser = new User (req.body.user);
        let registeredUser = await User.register(newUser,req.body.password);
        req.login(registeredUser, (err) => {
            if(err) {
                return next(err);
            }
            req.flash("success","Welcome to HotelHive!");
            let redirectUrl = res.locals.redirectUrl || "/listings";
            res.redirect(redirectUrl);
        });
    } catch (err) {
        req.flash("error",err.message);
        res.redirect("/signup");
    }
}

module.exports.renderLoginForm = (req,res) => {
    res.render("users/login.ejs");
}

module.exports.LoginUser = async (req,res) => {
    req.flash("success" ,"Welcome back to HotelHive!");
    let redirectUrl = res.locals.redirectUrl || "/listings";
    res.redirect(redirectUrl);
}

module.exports.LogoutUser = (req,res) => {
    req.logout((err) => {
        if(err) {
            return next(err);
        }
        req.flash("success","You are logged out!");
        res.redirect("/listings");
    });
}