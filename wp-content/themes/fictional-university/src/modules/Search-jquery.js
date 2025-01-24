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
        const baseUrl = universityData.root_url;
        const reqUrl = `${baseUrl}/wp-json/university/v1/search?term=${this.searchField.val()}`;

        $.getJSON(reqUrl, (res) => {
            const html = `
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                            ${
                                res.generalInfo.length
                                    ? '<ul class="link-list min-list">'
                                    : "<p>No general information matches that search.</p>"
                            }
                                ${res.generalInfo
                                    .map(
                                        (post) =>
                                            `<li><a href="${post.permalink}">${
                                                post.title
                                            }</a> ${
                                                post.postType == "post"
                                                    ? "by " + post.authorName
                                                    : ""
                                            }</li>`
                                    )
                                    .join("")}
                                    
                            ${res.generalInfo.length ? "</ul>" : ""}
                    </div>

                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                         ${
                             res.programs.length
                                 ? '<ul class="link-list min-list">'
                                 : `<p>No Programs matches that search. <a href='${baseUrl}/programs'>View All Program</a></p>`
                         }
                            ${res.programs
                                .map(
                                    (post) =>
                                        `<li><a href="${post.permalink}">${
                                            post.title
                                        }</a> ${
                                            post.postType == "post"
                                                ? "by " + post.authorName
                                                : ""
                                        }</li>`
                                )
                                .join("")}
                                
                        ${res.programs.length ? "</ul>" : ""}

                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${
                            res.professors.length
                                ? '<ul class="professor-cards">'
                                : `<p>No Professors matches that search.</p>`
                        }
                            ${res.professors
                                .map(
                                    (post) =>
                                        `
                                        <li class="professor-card__list-item">
                                            <a class="professor-card" href="${post.permalink}">
                                                <img class="professor-card__image" src="${post.image}" alt="">
                                                <span class="professor-card__name">${post.title}</span>
                                            </a>
                                        </li>
                                        `
                                )
                                .join("")}
                                
                        ${res.professors.length ? "</ul>" : ""}

                    </div>

                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${
                            res.campuses.length
                                ? '<ul class="link-list min-list">'
                                : `<p>No Campuses matches that search. <a href='${baseUrl}/campuses'>View All Campuses</a></p>`
                        }
                            ${res.campuses
                                .map(
                                    (post) =>
                                        `<li><a href="${post.permalink}">${
                                            post.title
                                        }</a> ${
                                            post.postType == "post"
                                                ? "by " + post.authorName
                                                : ""
                                        }</li>`
                                )
                                .join("")}
                                
                        ${res.campuses.length ? "</ul>" : ""}
                        
                        <h2 class="search-overlay__section-title">Events</h2>
                                ${
                                    res.events.length
                                        ? '<ul class="link-list min-list">'
                                        : `<p>No Campuses matches that search. <a href='${baseUrl}/campuses'>View All Campuses</a></p>`
                                }
                            ${res.events
                                .map(
                                    (post) =>
                                        `
                                        <div class="event-summary">
                                            <a class="event-summary__date event-summary__date--beige t-center" href="${post.permalink}">
                                                <span class="event-summary__month">${post.month}</span>
                                                <span class="event-summary__day">${post.day}</span>
                                            </a>
                                            <div class="event-summary__content">
                                                <h5 class="event-summary__title headline headline--tiny"><a href="${post.permalink}">${post.title}</a></h5>
                                                <p>
                                                    ${post.description}
                                                    <a href="${post.permalink}" class="nu gray">Read more</a>
                                                </p>
                                            </div>
                                        </div>
                                        `
                                )
                                .join("")}
                                
                        ${res.events.length ? "</ul>" : ""}
                    </div>
                </div>
            `;

            this.resultsDiv.html(html);
            this.isSpinnerVisible = false;
        }).fail(() => {
            this.resultsDiv.html("<p>Unexpected error; please try again.</p>");
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

// export default Search;
