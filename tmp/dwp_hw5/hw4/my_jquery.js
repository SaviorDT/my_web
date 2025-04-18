// 4001234567 蔡東霖 第4次作業 11/17
// 4001234567 Tony Tsai The Fourth Homework 11/17
function et(element_name) {
    if(element_name[0] == '#') {
        return document.getElementById(element_name.substring(1));
    }
    if(element_name[0] == '.') {
        return document.getElementsByClassName(element_name.substring(1));
    }
    return document.getElementsByTagName(element_name);
}