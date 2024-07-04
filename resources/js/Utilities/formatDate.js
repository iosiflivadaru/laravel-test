export default function formatDate(date, format = "ISO") {
    if (date) {
        date = new Date(date);
    }

    if (format == "ISO") {
        date = date.toISOString().split("T")[0];
    }
    // else if ==> keep adding formats in the future

    return date;
}
