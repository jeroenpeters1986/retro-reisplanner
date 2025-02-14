function t(c, f, a) {
    const d = document.getElementById(f);
    const b = document.getElementById(a);
    if (d.className == "dropC") {
        d.className = "dropE";
        b.innerHTML = "[+]"
    } else {
        d.className = "dropC";
        b.innerHTML = "[-]"
    }
    if (!c) {
        let c = window.event
    }
    c.cancelBubble = true;
    if (c.stopPropagation) {
        c.stopPropagation()
    }
    return false
}

function removeFav(a) {
    favs[a - 1] = "";
    sC()
}

function sC() {
    let $fc = "";
    for (let i = 0; i < favs.length; i++) {
        $fc += favs[i]
    }
    cC("fav", $fc, "365")
}

function cC(c, d, e) {
    let a = "";
    if (e) {
        let b = new Date();
        b.setTime(b.getTime() + (e * 86400000));
        a = "; expires=" + b.toGMTString()
    }
    document.cookie = c + "=" + d + a + ";"
};