// function formatRupiah(angka) {
//     var number_string = angka.toString().replace(/[^,\d]/g, ""),
//         split = number_string.split(","),
//         sisa = split[0].length % 3,
//         rupiah = split[0].substr(0, sisa),
//         ribuan = split[0].substr(sisa).match(/\d{3}/gi);

//     // Tambahkan titik jika yang di input sudah menjadi angka ribuan
//     if (ribuan) {
//         separator = sisa ? "." : "";
//         rupiah += separator + ribuan.join(".");
//     }

//     rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
//     return rupiah;
// }

function frmtRupiah(angka) {
    var reverse = angka.toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
    ribuan = ribuan.join('.').split('').reverse().join('');
    return 'Rp ' + ribuan;
}


function formatRupiah(amount) {
    // Convert amount to number if it's a string
    amount = typeof amount === 'string' ? parseFloat(amount) : amount;

    if (isNaN(amount)) {
        return 'Invalid number';
    }

    // Define thresholds for each unit
    const units = [
        { threshold: 1e12, suffix: 'T' },  // Trillion
        { threshold: 1e9, suffix: 'M' },   // Billion
        { threshold: 1e6, suffix: 'JT' },  // Million
        { threshold: 1e3, suffix: 'Ribu' } // Thousand
    ];

    // Find the appropriate unit
    for (const unit of units) {
        if (amount >= unit.threshold) {
            // Format the number with unit and return
            return (amount / unit.threshold).toFixed(2) + ' ' + unit.suffix;
        }
    }

    // If no unit is applicable, return the amount with Rupiah currency symbol
    return 'Rp ' + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&.');
}
