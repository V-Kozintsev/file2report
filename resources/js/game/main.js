// логика игры
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('game-canvas');
    const ctx = canvas.getContext('2d');

    /* ctx.fillStyle = "#e0e0e0"; */
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.fillStyle = "#333";
    ctx.font = "24px Arial";
    ctx.fillText("Это мой магазин!", 350, 100);


});
