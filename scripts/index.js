// get and update comments
let commentsCont = document.getElementsByClassName("comments-cont")[0];
let commentsCount = 10;
let scrolled = false;
let scroll = scrollY;
function loadComments() {
    fetch("arrays/comments.php")
        .then(response => response.json())
        .then(function (comments) {
            scroll = scrollY;
            commentsCont.innerHTML = "";
            for (let i = 1; i <= (comments.length > commentsCount ? commentsCount : comments.length); i++) {
                let name = comments[i - 1]["name"];
                let hasComment = name == document.querySelector(".hello span").textContent;
                let comment = comments[i - 1]["body"];
                let date = new Date(comments[i - 1]["date"].replace(/:(?=.*?\s)/g, "-").replace(/\s/, "T"));
                let id = comments[i - 1]["id"];
                let commentCont = document.createElement("div");
                commentCont.className = "comment";
                let commenter = document.createElement("h2");
                commenter.textContent = name;
                commentCont.append(commenter);
                let curve = document.createElement("span");
                curve.className = "curve";
                if (hasComment) {
                    curve.insertAdjacentHTML("beforeend", "<i class='fa-solid fa-circle-xmark fa-lg'></i>");
                    curve.querySelector("i").addEventListener("click", function () {
                        location.href = "?deleteCommentId=" + id + "&scroll=" + scrollY;
                    });
                }
                commentCont.append(curve);
                let milliseconds = Date.now() - date.getTime();
                let minutes = Math.round(milliseconds / 60_000);
                let hours = Math.round(minutes / 60);
                let days = Math.round(minutes / 1440);
                let months = Math.round(days / 30);
                let years = Math.round(days / 365);
                let commentDate = minutes < 1 ? "just now" : minutes < 60 ? "from " + minutes + " minutes" : 
                hours < 24 ? "from " + hours + " hours" : days < 30 ? "from " + days + " days" :
                months < 12 ? "from " + months + "months" : "from " + years + " years";
                let commentTime = document.createElement("span");
                commentTime.innerHTML = "<i class='fa-solid fa-clock'></i>" + commentDate;
                commentTime.className = "comment-time";
                commentCont.append(commentTime);
                let commentBody = document.createElement("div");
                commentBody.className = "comment-body";
                let commentArr = comment.replace(/\r/g, "").split("\n");
                for (let commentPart of commentArr) {
                    commentBody.appendChild(document.createTextNode(commentPart));
                    commentBody.appendChild(document.createElement("br"));
                }
                commentCont.append(commentBody);
                commentsCont.append(commentCont);
                curve.style.height = commenter.offsetHeight + "px";
            }
            scrollTo(0, scroll);
            if (/scroll=/.test(location.href) && !scrolled) {
                scrollTo(0, Number(location.href.split("scroll=")[1]));
                scrolled = true;
            }
        });
}
loadComments();
setInterval(loadComments, 3000);
// showing comments area
document.getElementsByClassName("add-comment")[0].addEventListener("click", function () {
    let commentArea = document.getElementsByClassName("new-comment")[0];
    commentArea.classList.toggle("display-comment-area");
    if (commentArea.getAttribute("aria-hidden") == "true") {
        commentArea.querySelector("textarea").focus();
        commentArea.setAttribute("aria-hidden", "false");
    } else {
        commentArea.setAttribute("aria-hidden", "true");
    }
})
//load more comments on button click
document.getElementsByClassName("more-comments")[0].addEventListener("click", function () {
    commentsCount += 10;
    if (commentsCount >= 50) {
        document.getElementsByClassName("more-comments")[0].remove();
    }
});