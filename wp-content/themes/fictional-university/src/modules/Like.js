import axios from "axios";

class Like {
    baseUrl = universityData.root_url;
    likeBoxEl = document.querySelector(".like-box");

    constructor() {
        if (this.likeBoxEl) {
            axios.defaults.headers.common["X-WP-Nonce"] = universityData.nonce;
            this.events();
        }
    }

    events() {
        this.likeBoxEl.addEventListener("click", (e) =>
            this.ourClickDispatcher(e)
        );
    }

    //methods
    ourClickDispatcher(e) {
        let currentLikeBox = e.target.closest(".like-box");

        if (currentLikeBox.getAttribute("data-exists").trim() === "yes") {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    async createLike(currentLikeBox) {
        try {
            const res = await axios({
                url: this.baseUrl + "/wp-json/university/v1/manageLike",
                method: "post",
                data: {
                    professorId: currentLikeBox
                        .getAttribute("data-professor")
                        .trim(),
                },
            });

            if (!(res.status === 200)) {
                throw new Error("Something Went wrong!");
            }

            currentLikeBox.setAttribute("data-exists", "yes");
            let likeCount = parseInt(
                currentLikeBox.querySelector(".like-count").textContent,
                10
            );
            likeCount++;
            currentLikeBox.querySelector(".like-count").textContent = likeCount;
            currentLikeBox.setAttribute("data-like", res.data);
            console.log(res.data);
        } catch (error) {
            console.error(error.message);
        }
    }

    async deleteLike(currentLikeBox) {
        try {
            const res = await axios({
                url: this.baseUrl + "/wp-json/university/v1/manageLike",
                method: "delete",
                data: {
                    like: currentLikeBox.getAttribute("data-like"),
                },
            });

            currentLikeBox.setAttribute("data-exists", "no");
            let likeCount = parseInt(
                currentLikeBox.querySelector(".like-count").textContent,
                10
            );
            likeCount--;
            currentLikeBox.querySelector(".like-count").textContent = likeCount;
            currentLikeBox.setAttribute("data-like", "");
            console.log(res.data);
        } catch (error) {
            console.error(error);
        }
    }
}

export default Like;
