$(document).ready(function () {
    fetchStudentData();
    fetchKelasData();
    fetchMataPelajaranData();
    fetchHistoryUjian();
});




    function fetchKelasData() {

        fetch('/kelas/getKelasData')
            .then(response => response.json())
            .then(data => {

                document.getElementById('kelas-content').textContent = data.total_kelas;
            })
            .catch(error => {
                console.error('Error fetching class data:', error);
            });
    }


    function fetchMataPelajaranData() {

        fetch('/mata-pelajaran/getMataPelajaranData')
            .then(response => response.json())
            .then(data => {

                document.getElementById('mata-pelajaran-content').textContent = data.total_mata_pelajaran;
            })
            .catch(error => {
                console.error('Error fetching class data:', error);
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
                        <td>${row.siswa_nis}</td>
                        <td>${row.siswa_name}</td>
                        <td>${row.kelas_name}</td>
                        <td>${row.created_at}</td>
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
    fetch('/siswa/getStudentData')
        .then(response => response.json())
        .then(data => {

            // Assuming data is an array of student objects
            let maleCount = 0;
            let femaleCount = 0;
            let totalCount = data.students.length; // Get total number of students

            // Initialize an object to hold the counts for each Rombel (Kelas)
            let rombelKelasCounts = {};

            data.students.forEach(student => {
                // Count male and female students
                if (student.jenis_kelamin === 'L') {
                    maleCount++;
                } else if (student.jenis_kelamin === 'P') {
                    femaleCount++;
                }

                // Count students by their kelas name
                let kelasName = student.kelas ? student.kelas.name : 'Unknown';

                if (rombelKelasCounts[kelasName]) {
                    rombelKelasCounts[kelasName]++;
                } else {
                    rombelKelasCounts[kelasName] = 1;
                }
            });

            // Safely update the counts in the UI
            let studentContentElement = document.getElementById('student-content');
            if (studentContentElement) {
                studentContentElement.textContent = `${totalCount}`;
            }

            let maleCountElement = document.getElementById('male-count');
            if (maleCountElement) {
                maleCountElement.textContent = `Laki-laki: ${maleCount}`;
            }

            let femaleCountElement = document.getElementById('female-count');
            if (femaleCountElement) {
                femaleCountElement.textContent = `Perempuan: ${femaleCount}`;
            }

            // Handle the Rombel and Kelas counts
            let rombelTableBody = document.getElementById('rombel-table-body');

            if (rombelTableBody) {
                // Clear existing rows
                rombelTableBody.innerHTML = '';

                // Populate the table with counts per Kelas
                for (let [kelasName, count] of Object.entries(rombelKelasCounts)) {
                    let row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${kelasName}</td>
                        <td>${count}</td>
                    `;
                    rombelTableBody.appendChild(row);
                }
            }
        })
        .catch(error => {
            console.error('Error fetching student data:', error);
        });
}

