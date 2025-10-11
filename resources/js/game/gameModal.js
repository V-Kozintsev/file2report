export default () => ({
  open: false,
  init() {
    console.log('Alpine компонент main инициализирован');
  },
  toggle() {
    this.open = !this.open;
    console.log('toggle called, open =', this.open);
  }
});
