module.exports.urlToCanvasData = (imageSrc, callback) => {
    let img = new Image;
    img.crossOrigin  = "Anonymous";
    img.src = imageSrc;
    img.onload = () => {
        var c = document.createElement('canvas');
        var ctx = c.getContext('2d');
        c.height = img.height;
        c.width = img.width;
        ctx.drawImage(img, 0, 0);
        var canvasData = c.toDataURL("image/png");
        callback(canvasData);
    }
};