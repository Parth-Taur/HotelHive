const Listing = require("./models/listing");
const ExpressError = require("./utils/ExpressError.js");
const {listingSchema , reviewSchema} = require("./schema.js");
const Review = require("./models/review.js");

module.exports.isLoggedIn = (req,res,next) => {
    // console.log(req.path, req.originalUrl);
    if(!req.isAuthenticated()){
        req.session.redirectUrl = req.originalUrl;
        req.flash("error", "Signup or Login to create new listings");
        res.redirect("/signup");
    }
    next();
};

module.exports.saveRedirectUrl = (req,res,next) => {
    if(req.session.redirectUrl) {
        res.locals.redirectUrl = req.session.redirectUrl;
    }
    next();
};

module.exports.isOwner = async(req,res,next) => {
    let { id } = req.params;
    const listing = await Listing.findById(id);
    if (!listing.owner._id.equals(res.locals.currUser._id)){
        req.flash("error","You are not the owner of this listing!");
        return res.redirect(`/listings/${id}`);
    }
    next();
};

module.exports.validateListing = (req,res,next) => {
    let { error } = listingSchema.validate(req.body);
    if (error) {
        let errMsg = error.details.map((el) => el.message).join(",");
        throw new ExpressError(400,errMsg);
    } else {
        next();
    }
};

module.exports.validateReview = (req,res,next) => {
    console.log(req.body);
    let { error } = reviewSchema.validate(req.body);
    if (error) {

        let errMsg = error.details.map((el) => el.message).join(",");
        throw new ExpressError(400,errMsg);
    } else {
        next();
    }
};

module.exports.isReviewAuthor = async(req,res,next) => {
    let { id, reviewId } = req.params;
    const review = await Review.findById(reviewId);
    if(res.locals.currUser) {
        if (!review.author._id.equals(res.locals.currUser._id)){
            req.flash("error","You are not the author of this review!");
            return res.redirect(`/listings/${id}`);
        }
    } else {
        req.flash("error","SignUp or Login to create or delete reviews!");
        return res.redirect(`/listings/${id}`);
    }
    next();
};