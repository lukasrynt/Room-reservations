class Filters {
    constructor(filterButton, filterContent) {
        this.shown = false;
        this.filterButton = filterButton;
        this.filterContent = filterContent;
        this.filterContentSize = 0;
    }

    trigger() {
        this.shown = !this.shown;
        if (this.shown) {
            this.filterButton.classList.add('active');
            this.filterContent.style.maxHeight = "calc(" + this.filterContent.scrollHeight + "px + 4rem)";
            this.filterContentSize = this.filterContent.scrollHeight;
        } else {
            this.filterButton.classList.remove('active');
            this.filterContent.style.maxHeight = null;
            this.filterContentSize = 0;
        }
    }

    resize() {
        if (this.shown && (this.filterContentSize !== this.filterContent.scrollHeight)) {
            this.filterContent.style.maxHeight = "calc(" + this.filterContent.scrollHeight + "px + 4rem)";
            this.filterContentSize = this.filterContent.scrollHeight;
            console.log("trigger");
        }

    }

    watch() {
        window.addEventListener('resize', function () {
            filtersInstance.resize();
        })
    }
}