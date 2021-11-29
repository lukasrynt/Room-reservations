class HamburgerMenu {
    constructor(nav) {
        this.nav = nav;
        this.hideButton = nav.getElementsByTagName('button')[0];
        this.itemList = [];

        let lis = nav.getElementsByTagName('ul')[0].getElementsByTagName('li');
        for (const li of lis) {
            this.itemList.push(li.getElementsByTagName('a')[0]);
        }
    }

    show() {
        this.nav.setAttribute('id', 'hamburger-menu');
        this.hideButton.classList.remove("hamburger-menu-close-hidden");
        this.itemList.forEach((x) => x.classList.add('hamburger-menu-item'));
    }

    hide() {
        this.nav.removeAttribute('id');
        this.hideButton.classList.add("hamburger-menu-close-hidden");
        this.itemList.forEach((x) => x.classList.remove('hamburger-menu-item'));
    }
}