// get and update comments
let commentsCont = document.getElementsByClassName("comments-cont")[0];
function loadComments() {
    fetch("arrays/comments.json")
        .then(response => response.json())
        .then(function (comments) {
            commentsCont.innerHTML = "";
            for (let [name, comment] of comments) {
                let commentCont = document.createElement("div");
                commentCont.className = "comment";
                let commenter = document.createElement("h2");
                commenter.textContent = name;
                commentCont.append(commenter);
                let curve = document.createElement("span");
                curve.className = "curve";
                commentCont.append(curve);
                let commentBody = document.createElement("div");
                commentBody.textContent = comment;
                commentCont.append(commentBody);
                commentsCont.append(commentCont);
            }
        });
}
loadComments();
setInterval(loadComments, 1000);
// showing comments area
document.getElementsByClassName("add-comment")[0].addEventListener("click", function () {
    document.getElementsByClassName("new-comment")[0].classList.toggle("display-comment-area");
})