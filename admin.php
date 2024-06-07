<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Primary jsPDF CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha384-29XlPSe3s4DF+p3hRAkXnG49iHzjlPJwINo1Rt2eYPXQ9d7xtl43A+JfAxz5O/FJ" crossorigin="anonymous"></script>
    <!-- Fallback jsPDF CDN -->
    <script>
        if (typeof window.jspdf === 'undefined') {
            document.write('<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"><\/script>');
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Feedback</h1>
        <a href="download_excel.php" class="btn btn-primary mb-3">Download Excel</a>
        <button id="download-pdf" class="btn btn-secondary mb-3">Download PDF</button>
        <table id="feedback-table" class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Rate</th>
                    <th>Suggestions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root"; // Change this to your database username
                $password = ""; // Change this to your database password
                $dbname = "feedback"; // Change this to your database name

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Updated SQL query to order by ID in descending order
                $sql = "SELECT id, customerName, customerPhoneNumber, customerRate, customerSuggestions FROM vishallfeedback ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["customerName"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["customerPhoneNumber"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["customerRate"]) . "</td>";
                        echo "<td class='suggestions'>" . htmlspecialchars($row["customerSuggestions"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const downloadPdfButton = document.getElementById('download-pdf');

            downloadPdfButton.addEventListener('click', function () {
                if (window.jspdf && window.jspdf.jsPDF) {
                    const { jsPDF } = window.jspdf;
                    let doc = new jsPDF();
                    doc.setFontSize(10);  // Set font size to 10
                    let pageHeight = doc.internal.pageSize.height;
                    let y = 20;

                    doc.text("Feedback", 10, 10);

                    // Get table HTML
                    const table = document.getElementById('feedback-table');
                    const rows = table.querySelectorAll('tr');

                    const colWidths = [20, 40, 40, 20, 80]; // Widths of each column

                    rows.forEach(function (rowElement, rowIndex) {
                        const cols = rowElement.querySelectorAll('th, td');
                        let col = 10;
                        let maxHeight = 0;

                        cols.forEach(function (colElement, colIndex) {
                            let text = colElement.innerText;
                            let textLines = doc.splitTextToSize(text, colWidths[colIndex] - 2);

                            textLines.forEach(function (line) {
                                if (y + 6 > pageHeight) {
                                    doc.addPage();
                                    y = 20;
                                    doc.text("Feedback", 10, 10); // Re-add title at the top of each new page
                                }

                                if (rowIndex === 0) {
                                    doc.text(line, col + 1, y + 5, { maxWidth: colWidths[colIndex] - 2 });
                                } else {
                                    doc.text(line, col + 1, y + 5, { maxWidth: colWidths[colIndex] - 2 });
                                }

                                y += 6;
                                maxHeight = Math.max(maxHeight, y);
                            });

                            col += colWidths[colIndex];
                            y -= 6 * textLines.length;
                        });

                        y = maxHeight + 5;
                    });

                    doc.save('feedback.pdf');
                } else {
                    alert('jsPDF library is not loaded correctly.');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
