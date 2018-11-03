export default class Brush{
    constructor(size, color, maxsize, minsize){
        this.size = size;    
        this.maxsize = maxsize;
        this.minsize = minsize; 
        this.type = ""; 
        this.color = color;   
    }

    pencil(x1, y1, x2, y2) {
        this.context.beginPath();
        this.context.moveTo(x1, y1);
        this.context.lineTo(x2, y2);
        this.context.stroke();
    }

    eraser(x1, y1, x2, y2) {
        var color = this.context.strokeStyle;
        
        this.context.lineWidth *=2;
        this.context.strokeStyle = "white";
        this.context.beginPath();
        this.context.moveTo(x1, y1);
        this.context.lineTo(x2, y2);
        this.context.stroke();
    
        this.context.strokeStyle = color;
        this.context.lineWidth /=2;
    }

    marker(x1, y1, x2, y2) {
        this.context.globalAlpha = 0.8;
        var color = this.context.strokeStyle;
       
        this.context.beginPath();
        this.context.moveTo(x1, y1);
        this.context.lineTo(x2, y2);
        this.context.stroke();
    
        this.context.beginPath();
        this.context.lineWidth = this.size / 2;
        this.context.strokeStyle = "white"; // for small middle line
        this.context.moveTo(x1, y1);
        this.context.lineTo(x2, y2);
        this.context.stroke();  
    
        this.context.lineWidth *= 2;
        this.context.globalAlpha = 1;
        this.context.strokeStyle = color;
    }

    chalk(x1, y1, x2, y2) {
    
        this.context.beginPath();
        this.context.moveTo(x1, y1);
        this.context.lineTo(x2, y2);
        this.context.stroke();
    
        // Chalk Effect
        var length = Math.round(Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2)) / (5 / this.size));
        var xUnit = (x2 - x1) / length;
        var yUnit = (y2 - y1) / length;
        for (var i = 0; i < length; i++) {
            var xCurrent = x1 + (i * xUnit);
            var yCurrent = y1 + (i * yUnit);
            var xRandom = xCurrent + (Math.random() - 0.5) * this.size * 1.2;
            var yRandom = yCurrent + (Math.random() - 0.5) * this.size * 1.2;
            this.context.clearRect(xRandom, yRandom, Math.random() * 2 + 2, Math.random() + 1);
        }
    }

    spray(x, y) {
        offsetX = this.size;
        offsetY = this.size;
    
        for (var i = this.size*2; i--;) {
            var offsetX = this.generateRandom(-this.size, this.size);
            var offsetY = this.generateRandom(-this.size, this.size);
            this.context.fillRect(x + offsetX, y + offsetY, 1, 1);
        }
    }

    doubleBrush(x1, y1, x2, y2) {

        var offset = this.size;
        this.context.globalAlpha = this.size/5;
    
        //upper
        this.context.beginPath();
        this.context.moveTo(x1 - this.generateRandom(0, offset), y1 - this.generateRandom(0, offset));
        this.context.lineTo(x2 - this.generateRandom(0, offset), y2 - this.generateRandom(0, offset));
        this.context.stroke();
    
        //mid
        this.context.beginPath();
        this.context.moveTo(x1, y1);
        this.context.lineTo(x2, y2);
        this.context.stroke();
    
        //lower
        this.context.beginPath();
        this.context.moveTo(x1 + this.generateRandom(0, offset), y1 + this.generateRandom(0, offset));
        this.context.lineTo(x2 + this.generateRandom(0, offset), y2 + this.generateRandom(0, offset));
        this.context.stroke();
    
         this.context.globalAlpha = 1;
    }

    changeBrush(name){
        if(name === "pencil"){
            this.draw = this.pencil;
        }
        else if(name === "marker"){
            this.draw = this.marker;
        }
        else if(name === "chalk"){
            this.draw = this.chalk;
        }
        else if(name === "spray"){
            this.draw = this.spray;
        }
        else if(name === "doubleBrush"){
            this.draw = this.doubleBrush;
        }
        else if(name === "eraser"){
            this.draw = this.eraser;
        }
       
        this.type = name; 
    }

    changeColor(color){
        this.color = color;
        this.context.strokeStyle = color;
        this.context.fillStyle = color;
    }

    changeSize(size){
        this.size = size;
        this.context.lineWidth = parseInt(size);
    }

    setContext(ctx){
        this.context = ctx;
    }

    getType(){
        return this.type;
    }

    generateRandom(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    clear() {
        this.context.clearRect(0, 0, this.context.canvas.width, this.context.canvas.height);    
    }
} 