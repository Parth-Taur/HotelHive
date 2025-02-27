const cloudinary = require('cloudinary').v2;
const { CloudinaryStorage } = require('multer-storage-cloudinary');


cloudinary.config({
    cloud_name: process.CLOUD_NAME,
    api_key: process.CLOUD_API_KEY,
    api_secret: process.CLOUD_API_SECRET
});

const storage = new CloudinaryStorage({
    cloudinary: cloudinary,
    params: {
      folder: 'hotelHive_DEV',
      allowedFormats: ["png","jpg","jpeg"],
    },
});

module.exports = {
    cloudinary,
    storage,
};