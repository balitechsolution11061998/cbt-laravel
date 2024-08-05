$(document).ready(function () {
    fetchStudentData();
    fetchKelasData();
    fetchRombelData();
    // fetchMataPelajaranData();
    fetchHistoryUjian();
});




    function fetchKelasData() {
        document.getElementById('spinner-kelas').style.display = 'block';

        fetch('/kelas/getKelasData')
            .then(response => response.json())
            .then(data => {
                document.getElementById('spinner-kelas').style.display = 'none';

                document.getElementById('kelas-content').textContent = data.total_kelas;
            })
            .catch(error => {
                console.error('Error fetching class data:', error);
                document.getElementById('spinner-kelas').style.display = 'none';
            });
    }

    function fetchRombelData() {
        document.getElementById('spinner-rombel').style.display = 'block';

        fetch('/rombel/getRombelData')
            .then(response => response.json())
            .then(data => {
                document.getElementById('spinner-rombel').style.display = 'none';

                document.getElementById('rombel-content').textContent = data.rombelCounts;


            })
            .catch(error => {
                console.error('Error fetching rombel data:', error);
                document.getElementById('spinner-rombel').style.display = 'none';
            });
    }




function fetchHistoryUjian() {
    $.ajax({
        url: '/ujian/fetchHistory',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            let tableBody = $('#historyUjian-table-body');
            tableBody.empty(); // Clear any existing data

            // Iterate over the data and append rows to the table
            data.forEach(function(row) {
                tableBody.append(`
                    <tr>
                        <td>${row.siswa_name}</td>
                        <td>${row.rombel_name} - ${row.kelas_name}</td>
                        <td>${row.jumlah_benar}</td>
                        <td>${row.jumlah_salah}</td>
                        <td>${row.total_nilai}</td>
                    </tr>
                `);
            });

            $('#spinner-detail').hide(); // Hide spinner after data is loaded
        },
        error: function() {
            $('#spinner-detail').hide();
            alert('Failed to load data');
        }
    });
}


function fetchStudentData() {
    document.getElementById('spinner-student').style.display = 'block';

    fetch('/siswa/getStudentData')
        .then(response => response.json())
        .then(data => {
            document.getElementById('spinner-student').style.display = 'none';

            document.getElementById('student-content').textContent = data.total;
            document.getElementById('male-count').textContent = `Laki-laki: ${data.male}`;
            document.getElementById('female-count').textContent = `Perempuan: ${data.female}`;

            // Handle the Rombel and Kelas counts
            let rombelTableBody = document.getElementById('rombel-table-body');

            // Clear existing rows
            rombelTableBody.innerHTML = '';

            for (let [rombelKelas, count] of Object.entries(data.rombelKelasCounts)) {
                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${rombelKelas}</td>
                    <td>${count}</td>
                `;
                rombelTableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error fetching student data:', error);
            document.getElementById('spinner-student').style.display = 'none';
        });
}
