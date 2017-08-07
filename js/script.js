function convertToOriginalDate(dateString){
    var parts = dateString.split('-');

    var combineParts = parts[2] + '-' + parts[1] + '-' + parts[0];

    return combineParts;
}
