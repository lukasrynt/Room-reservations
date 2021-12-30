class HamburgerMenu {
    constructor(main, header) {
        this.main = main;
        this.header = header;
        this.logo = header.getElementsByTagName('h1')[0];
        this.nav = header.getElementsByTagName('nav')[0];
        this.hideButton = header.getElementsByTagName('button')[0];
        this.section = header.getElementsByTagName('section')[0];
        this.showButton = header.getElementsByTagName('button')[1];
        this.itemList = [];

        let lis = this.nav.getElementsByTagName('ul')[0].getElementsByTagName('li');
        for (const li of lis) {
            this.itemList.push(li.getElementsByTagName('a')[0]);
        }

        lis = header.getElementsByClassName('dropdown-content')[0].getElementsByTagName('a');
        for (const a of lis) {
            this.itemList.push(a);
        }
    }

    show() {
        this.main.classList.add('main-hidden');
        this.header.setAttribute('id', 'hamburger-menu');
        this.logo.setAttribute('id', 'hamburger-menu-logo');
        this.hideButton.classList.remove('hamburger-menu-close-hidden');
        this.section.classList.add('hamburger-menu-user-links');
        this.showButton.classList.add('hamburger-menu-close-hidden');
        this.itemList.forEach((x) => x.classList.add('hamburger-menu-item'));
    }

    hide() {
        this.main.classList.remove('main-hidden');
        this.header.removeAttribute('id');
        this.logo.removeAttribute('id', 'hamburger-menu-logo');
        this.hideButton.classList.add("hamburger-menu-close-hidden");
        this.section.classList.remove('hamburger-menu-user-links');
        this.showButton.classList.remove('hamburger-menu-close-hidden');
        this.itemList.forEach((x) => x.classList.remove('hamburger-menu-item'));
    }

    watch() {
        window.addEventListener('resize', function () {
            if (window.innerWidth > 740)
                hamburgerMenuInstance.hide()
        })
    }
}