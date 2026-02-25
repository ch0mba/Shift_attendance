<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Details - ZKTeco System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Attendance Details</h1>
                <a href="index.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Back to Dashboard
                </a>
            </div>

            <div class="mb-4">
                <input type="date" id="datePicker" class="border rounded-lg px-4 py-2">
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Employee</th>
                            <th class="py-3 px-4 text-left">Shift</th>
                            <th class="py-3 px-4 text-left">Clock In</th>
                            <th class="py-3 px-4 text-left">Clock Out</th>
                            <th class="py-3 px-4 text-left">Duration</th>
                            <th class="py-3 px-4 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceDetails">
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">Select a date</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function loadAttendance(date) {
            $.get(`api/get-attendance.php?date=${date}`, function(data) {
                if (data.success) {
                    let tableHtml = '';
                    
                    data.data.forEach(record => {
                        const statusClass = record.status === 'active' ? 'bg-green-100 text-green-800' : 
                                          record.status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                          'bg-gray-100 text-gray-800';
                        
                        tableHtml += `
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">${record.user_name}</td>
                                <td class="py-3 px-4">${record.shift_name}</td>
                                <td class="py-3 px-4">${record.clock_in_time || '-'}</td>
                                <td class="py-3 px-4">${record.clock_out_time || '-'}</td>
                                <td class="py-3 px-4">${record.duration || '-'}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs ${statusClass}">
                                        ${record.status_text}
                                    </span>
                                </td>
                            </tr>
                        `;
                    });

                    if (data.data.length === 0) {
                        tableHtml = '<tr><td colspan="6" class="py-4 text-center text-gray-500">No attendance records for this date</td></tr>';
                    }

                    $('#attendanceDetails').html(tableHtml);
                }
            });
        }

        $(document).ready(function() {
            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            $('#datePicker').val(today);
            loadAttendance(today);

            $('#datePicker').on('change', function() {
                loadAttendance($(this).val());
            });
        });
    </script>
</body>
</html>