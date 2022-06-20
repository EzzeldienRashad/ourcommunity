if (document.documentElement.clientWidth > 900) {
    document.getElementsByClassName("hello")[0].style.left =
        document.documentElement.clientWidth / 2 - document.getElementsByClassName("hello")[0]
            .offsetWidth / 2 + "px";
}
document.getElementsByTagName("main")[0].style.minHeight =
    document.documentElement.clientHeight - 80 + "px";