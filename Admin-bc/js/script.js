function ValidarData(data) {
    var splitData = data.split("/");
    var dt = new Date();
    if (splitData.length === 3) {
        if (splitData[0] >= 01 && splitData[0] <= 31) {
            if (splitData[1] >= 01 && splitData[1] <= 12) {
                if (splitData[2] >= (dt.getFullYear() - 100) && splitData[2] <= (dt.getFullYear() - 10)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function delete_cookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}