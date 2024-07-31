function fetchDataProvinsi(selectedProvinsiIds) {
    $('#provinsi').select2({
        placeholder: 'Select a provinsi',
        allowClear: true,
        dropdownParent: $('#mdlForm'),
        ajax: {
            url: '/provinsi/data', // Ganti dengan endpoint API sesungguhnya
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data.items, function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    }),
                    pagination: {
                        more: (params.page * 10) < data.total_count
                    }
                };
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        var provinsiId = e.params.data.id; // Dapatkan ID opsi yang dipilih
        fetchDataKabupaten(provinsiId); // Ambil data kabupaten berdasarkan provinsi yang dipilih

        // Contoh tindakan lain berdasarkan seleksi
        console.log('Selected provinsi ID:', provinsiId);
    });

    // Jika selectedProvinsiIds ada, pilih provinsi secara otomatis
    if (selectedProvinsiIds) {
        // Tetapkan nilai dan trigger event change
        $('#provinsi').val(selectedProvinsiIds).trigger('change');
    }
}



function fetchDataKabupaten(provinsiId) {
    $('#kabupaten').select2({
        placeholder: 'Select a kabupaten',
        allowClear: true,
        ajax: {
            url: '/kabupaten/data', // Replace with your actual API endpoint
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    provinsi_id: provinsiId,
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data.items, function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    }),
                    pagination: {
                        more: (params.page * 10) < data.total_count
                    }
                };
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        var kabupatenId = $(this).val();
        fetchDataKecamatan(kabupatenId);
    });
}

function fetchDataKecamatan(kabupatenId) {
    $('#kecamatan').select2({
        placeholder: 'Select a kecamatan',
        allowClear: true,
        ajax: {
            url: '/kecamatan/data', // Replace with your actual API endpoint
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    kabupaten_id: kabupatenId,
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data.items, function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    }),
                    pagination: {
                        more: (params.page * 10) < data.total_count
                    }
                };
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        var kecamatanId = $(this).val();
        fetchDataKelurahan(kecamatanId);
    });
}



function fetchDataKelurahan(kecamatanId) {
    $('#kelurahan').select2({
        placeholder: 'Select a kelurahan',
        allowClear: true,
        ajax: {
            url: '/kelurahan/data', // Replace with your actual API endpoint
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    kecamatan_id: kecamatanId,
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data.items, function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    }),
                    pagination: {
                        more: (params.page * 10) < data.total_count
                    }
                };
            },
            cache: true
        }
    });
}
