/* Caleb Yarborough */
"use strict";

// Add event listener to the print button
document.addEventListener("DOMContentLoaded", () => {
    const printButton = document.getElementById("PrintButton");
    if (printButton) {
        printButton.addEventListener("click", () => {
            print(); 
        });
    }
});