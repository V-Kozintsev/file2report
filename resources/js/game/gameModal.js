export default () => ({
    open:false,
    toggle(){
        this.open = !this.open
        if(this.open){
            this.$nextTick(()=>{this.drawCanwas()})
        }
    },
    drawCanwas(){
        console.log('показать модуль!');
    }
});
