function toNepaliDigits(numberStr) {
    const englishDigits = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
    const nepaliDigits = ["०", "१", "२", "३", "४", "५", "६", "७", "८", "९"];
    return numberStr
        .split("")
        .map((char) => {
            const index = englishDigits.indexOf(char);
            return index > -1 ? nepaliDigits[index] : char;
        })
        .join("");
}

document.addEventListener("DOMContentLoaded", function () {
    if (typeof NepaliFunctions !== "undefined" && NepaliFunctions.BS) {
        try {
            var current_date = NepaliFunctions.BS.GetCurrentDate("YYYY-MM-DD");
            var current_day =
                NepaliFunctions.BS.GetFullDayInUnicode(current_date);
            var nepali_date = toNepaliDigits(current_date);

            var bsDisplay = document.getElementById("nepali-today-bs");
            if (bsDisplay) {
                bsDisplay.textContent = nepali_date + ", " + current_day;
            }
        } catch (e) {
            console.error("Failed to get BS date:", e);
        }
    }
});
