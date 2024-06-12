const express = require("express");
const router = express.Router({ mergeParams : true });
const wrapAsync = require("../utils/wrapAsync.js");
const {isLoggedIn,isOwner,validateListing} = require("../middleware.js");
const ListingController = require("../controllers/listings.js");
const multer  = require('multer');
const {storage} = require("../cloudConfig.js");
const upload = multer({ storage });

router.get("/new",
    isLoggedIn, 
    ListingController.renderNewForm
);

router.route("/")
    .get( 
        wrapAsync(ListingController.index)
    )
    // .post(
    //     isLoggedIn, 
    //     validateListing , 
    //     wrapAsync(ListingController.createNewListing)
    // );
    .post(upload.single("listing[image]"),(req,res) => {
        res.send(req.file);
    });


router.route("/:id")
    .get( 
        wrapAsync(ListingController.showListing)
    )
    .put(
        isLoggedIn, 
        isOwner, 
        validateListing, 
        wrapAsync(ListingController.editListing)
    )
    .delete(
        isLoggedIn,
        isOwner, 
        wrapAsync(ListingController.deleteListing)
    );

//edit route
router.get("/:id/edit",
    isLoggedIn,isOwner, 
    wrapAsync(ListingController.renderEditForm)
);

module.exports = router;