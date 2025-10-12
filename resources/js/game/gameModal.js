export default function gameModal() {
    return {
        open: false,
        x: 50,
        y: 50,
        speed: 10,

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => {
                    this.setupCanvas();
                    this.drawCanvas();
                    window.addEventListener('keydown', this.moveSquare.bind(this));
                    window.addEventListener('resize', this.onResize.bind(this));
                });
            } else {
                window.removeEventListener('keydown', this.moveSquare.bind(this));
                window.removeEventListener('resize', this.onResize.bind(this));
            }
        },

        setupCanvas() {
            const canvas = document.getElementById('game-canvas');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        },

        onResize() {
            this.setupCanvas();
            this.drawCanvas();
        },

        drawCanvas() {
            const canvas = document.getElementById('game-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#2999ff";
            ctx.fillRect(this.x, this.y, 80, 80);
        },

        moveSquare(event) {
            const canvas = document.getElementById('game-canvas');
            switch (event.key) {
                case 'ArrowUp':
                    this.y = Math.max(0, this.y - this.speed);
                    break;
                case 'ArrowDown':
                    this.y = Math.min(canvas.height - 80, this.y + this.speed);
                    break;
                case 'ArrowLeft':
                    this.x = Math.max(0, this.x - this.speed);
                    break;
                case 'ArrowRight':
                    this.x = Math.min(canvas.width - 80, this.x + this.speed);
                    break;
            }
            this.drawCanvas();
        }
    }
}
