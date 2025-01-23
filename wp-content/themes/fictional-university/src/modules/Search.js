import $ from "jquery";

class Search {
    //1. Describe and create/initiate our object
    constructor() {
        this.resultsDiv = $("#search-overlay__results");
        this.openBtn = $(".js-search-trigger");
        this.closeBtn = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.previousValue;
        this.typingTimer;

        this.events();
    }
    //2. Add events
    events() {
        this.openBtn.on("click", this.openOverlay.bind(this));
        this.closeBtn.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    //3. Methods (function, action...)
    typingLogic() {
        if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);

            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }

                this.typingTimer = setTimeout(this.getResults.bind(this), 500);
            } else {
                this.resultsDiv.html("");
                this.isSpinnerVisible = false;
                return;
            }
        }

        this.previousValue = this.searchField.val();
    }

    getResults() {
        const url =
            universityData.root_url +
            `/wp-json/wp/v2/posts?search=${this.searchField.val()}`;
        $.getJSON(url, (posts) => {
            $.getJSON(
                universityData.root_url +
                    `/wp-json/wp/v2/pages?search=${this.searchField.val()}`,
                (pages) => {
                    posts = posts.concat(pages);

                    this.resultsDiv.html(
                        `
                <h2 class="search-overlay__section-title">General Information</h2>
                ${
                    posts.length
                        ? '<ul class="link-list min-list">'
                        : "<p>No general information matches that search.</p>"
                }
                    ${posts
                        .map(
                            (post) =>
                                `<li><a href="${post.link}">${post.title.rendered}</a></li>`
                        )
                        .join("")}
                        
                ${posts.length ? "</ul>" : ""}
                `
                    );

                    this.isSpinnerVisible = false;
                }
            );
        });
    }

    keyPressDispatcher(e) {
        if (
            e.keyCode == 83 &&
            !this.isOverlayOpen &&
            !$("input, textarea").is(":focus")
        ) {
            this.openOverlay();
        }

        if (
            e.keyCode == 27 &&
            this.isOverlayOpen &&
            !$("input, textarea").is(":focus")
        ) {
            this.closeOverlay();
        }
    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val("");
        this.isOverlayOpen = true;
        setTimeout(() => $(this.searchField).trigger("focus"), 301);
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;
    }
}

export default Search;
