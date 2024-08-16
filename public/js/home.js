$(document).ready(function () {
    fetchStudentData();
    fetchKelasData();
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

            // Update the counts in the UI
            document.getElementById('student-content').textContent = `${totalCount}`;
            document.getElementById('male-count').textContent = `Laki-laki: ${maleCount}`;
            document.getElementById('female-count').textContent = `Perempuan: ${femaleCount}`;

            // Handle the Rombel and Kelas counts
            let rombelTableBody = document.getElementById('rombel-table-body');

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
        })
        .catch(error => {
            console.error('Error fetching student data:', error);
            document.getElementById('spinner-student').style.display = 'none';
        });
}
