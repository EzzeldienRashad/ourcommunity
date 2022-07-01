// get and update comments
let commentsCont = document.getElementsByClassName("comments-cont")[0];
let commentsCount = 10;
function loadComments() {
    fetch("arrays/comments.php")
        .then(response => response.json())
        .then(function (comments) {
            let scroll = scrollY;
            commentsCont.innerHTML = "";
            for (let i = 1; i <= (comments.length > commentsCount ? commentsCount : comments.length); i++) {
                let name = comments[i - 1]["name"];
                let comment = comments[i - 1]["body"];
                let commentCont = document.createElement("div");
                commentCont.className = "comment";
                let commenter = document.createElement("h2");
                commenter.textContent = name;
                commentCont.append(commenter);
                let curve = document.createElement("span");
                curve.className = "curve";
                commentCont.append(curve);
                let commentBody = document.createElement("div");
                let commentArr = comment.replace(/\r/g, "").split("\n");
                for (let commentPart of commentArr) {
                    commentBody.appendChild(document.createTextNode(commentPart));
                    commentBody.appendChild(document.createElement("br"));
                }
                commentCont.append(commentBody);
                commentsCont.append(commentCont);
                curve.style.height = commenter.offsetHeight + "px";
                scrollTo(0, scroll);
            }
        });
}
loadComments();
setInterval(loadComments, 5000);
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