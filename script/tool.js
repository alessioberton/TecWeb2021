//
// window.onload = function() {
//
//   test();
// }
//
//
// function test() {
//     const mySel = document.getElementById("option_film_visti");
//     mySel.addEventListener("change", function () {
//         localStorage.setItem("selValue", this.value);
//     });
//     let val = localStorage.getItem("selValue");
//     if (val) mySel.value = val; // set the dropdown
// // trigger the change in case there are other events on the select
// //     mySel.onchange();
// }