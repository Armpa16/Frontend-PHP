<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$servername = "yamabiko.proxy.rlwy.net";
$port=13821;
$username = "root";
$password = "EKRiEnzCXzuGRzsXOanDuXnpFPvzKpOv";
$dbname = "railway";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบเข้าระบบ
if (isset($_SESSION['username'])) {
} else {
    header("Location: loginform.php");
    exit();
}

// ดึงข้อมูลจากฐานข้อมูล
$username = $_SESSION['username'];

// 🔥 JOIN users กับ health_profile โดยใช้ user_id
$sql = "SELECT p.daily_calorie, p.age, p.weight, p.height, p.activity_level, p.bmi, p.status_bmi, p.diseases
        FROM users u 
        JOIN profiles p ON u.users_id = p.users_id 
        WHERE u.username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $daily_calorie = $row['daily_calorie'];
    $age = $row['age'];
    $weight = $row['weight'];
    $height = $row['height'];
    $activity_level = $row['activity_level'];
    $bmi = $row['bmi'];
    $status_bmi = $row['status_bmi'];
    $diseases = $row['diseases'];
} else {
    $daily_calorie = "ไม่ระบุ";
    $age = "ไม่ระบุ";
    $weight = "ไม่ระบุ";
    $height = "ไม่ระบุ";
    $activity_level = "ไม่ระบุ";
    $bmi = "ไม่ระบุ";
    $status_bmi = "ไม่ระบุ";
    $diseases = "ไม่ระบุ";
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/index.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>const loggedInUser = "<?php echo $username; ?>"; // ส่งค่าไปที่ JavaScript</script>
</head>
<body>
    <div class="container">
        <button id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <!-- เเถบบาร์ด้านข้าง -->
        <div class="bar flex-column p-0" style="width: 310px; height: 1150px;">
            <div class="text-center py-4">
                <i class="fa-solid fa-circle-user fs-1"></i>
                <div class="fs-4 mt-2" style="font-size: 25px; font-weight:bold;"><?php echo htmlspecialchars($_SESSION['username']);?></div>
            </div>
            <!-- เมนู -->
            <ul class="nav nav-pills flex-column px-0">
                <li class="nav-item mb-3">
                    <a href="index.php" class="nav-link active custom-nav">
                        <i class="fa-solid fa-house"></i>&nbsp;&nbsp;&nbsp;&nbsp;หน้าหลัก
                    </a>
                </li>
                <li class="mb-3">
                    <a href="recommendfood.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-solid fa-bowl-food"></i>&nbsp;&nbsp;&nbsp;&nbsp;การแนะนำอาหาร
                    </a>
                </li>
                <li class="mb-3">
                    <a href="calendar.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-regular fa-calendar"></i>&nbsp;&nbsp;&nbsp;&nbsp;ปฏิทินการรับประทานอาหาร
                    </a>
                </li>
                <li class="mb-3">
                    <a href="profile.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-regular fa-circle-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;โปรไฟล์
                    </a>
                </li>
            </ul>
            <li class="mt-auto text-center ">
                <a href="loginform.php" class="nav-link link-body-emphasis custom-nav">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>&nbsp;&nbsp;ออกจากระบบ
                </a>
            </li>
        </div>


        <div class="content">
            <h1 class="texthealth">ข้อมูลสุขภาพของคุณ</h1>
            <div class="showinfo">
                <div class="info_user bg-body-tertiary">
                    <h1>ปริมาณเเคลอรี่ที่ต้องการ</h1><br>
                    <div class="calorie_show">
                        <div class="cercle">
                            <i class="fa-solid fa-fire"></i>
                        </div>
                        <div class="cal_per_day">
                            <?php echo htmlspecialchars($daily_calorie); ?>&nbsp; กิโลเเคลอรี่
                            <div class="progress">
                                <div class="per"></div>
                            </div>
                        </div>
                        <p>/วัน</p>
                    </div>
                    <div class="statusinfo_show">
                        <span style="color: white;">
                            <?php echo htmlspecialchars($age); ?><br>
                            <p>อายุ</p>
                        </span>
                        <span style="color: white;">
                            <?php echo htmlspecialchars($weight); ?><br>
                            <p>น้ำหนัก</p>
                        </span>
                        <span style="color: white;">
                            <?php echo htmlspecialchars($height); ?><br>
                            <p>ส่วนสูง</p>
                        </span>
                        <span style="color: white;">
                            <?php echo htmlspecialchars($activity_level); ?><br>
                            <p>ระดับกิจกรรม</p>
                        </span>
                    </div>
                </div> 
                <!-- info_user -->

                <div class="box-small">
                    <div class="bmi bg-body-tertiary">
                        <h1>BMI</h1><br>
                        <div class="bmi_show">
                            <div class="cercle">
                                <i class="fa-solid fa-weight-scale"></i>
                            </div>
                            <div class="bmi-text-container">
                                <?php echo htmlspecialchars($bmi); ?><br>
                                <?php echo htmlspecialchars($status_bmi); ?>
                            </div>
                        </div>
                    </div>
                    <!-- bmi -->

                    <div class="dis bg-body-tertiary">
                        <h1>โรคประจำตัว</h1><br>
                        <div class="diseases_show">
                            <div class="cercle">
                                <i class="fa-solid fa-heart-pulse"></i>
                            </div>
                            <div class="disease-list">
                                <?php
                                $diseasesArray = explode(',', htmlspecialchars($diseases));
                                foreach ($diseasesArray as $disease) {
                                    echo "<span>$disease</span>";
                                }
                                ?>
                            </div>
                        </div>
                    </div> 
                    <!-- dis -->
                </div>
            </div>
            <h1>แคลอรี่ที่ได้รับในแต่ละวัน</h1>
            <div class="cal_day">
                <div class="card chart-container">
                    <div class="calendar-controls">
                        <!-- <button >◀ ก่อนหน้า</button> -->
                        <button onclick="changeWeek(-1)">◀ ก่อนหน้า</button>
                        <span id="week-label"></span>
                        <!-- <button >ถัดไป ▶</button> -->
                        <button onclick="changeWeek(1)">ถัดไป ▶</button>
                    </div>
                    <canvas id="calorieChart"></canvas>
                </div>
            </div>
            <h1>สารอาหารที่ได้รับในแต่ละวัน</h1>
                <div class="nutrients_day">
                    <div class="card chart-container">
                        <div class="calendar-controls">
                            <!-- <button >◀ ก่อนหน้า</button> -->
                            <button onclick="changeWeekProtein(-1)">◀ ก่อนหน้า</button>
                            <span id="week-label-protein"></span>
                            <!-- <button >ถัดไป ▶</button> -->
                            <button onclick="changeWeekProtein(1)">ถัดไป ▶</button>
                        </div>
                        <canvas id="ProteinChart"></canvas>
                    </div>
                    <div class="card chart-container">
                        <div class="calendar-controls">
                            <!-- <button >◀ ก่อนหน้า</button> -->
                            <button onclick="changeWeekCarbohydrate(-1)">◀ ก่อนหน้า</button>
                            <span id="week-label-carb"></span>
                            <!-- <button >ถัดไป ▶</button> -->
                            <button onclick="changeWeekCarbohydrate(1)">ถัดไป ▶</button>
                        </div>
                        <canvas id="CarbohydrateChart"></canvas>
                    </div>
                </div>
                <!-- กราฟเเถว 2 -->
                <div class="nutrients_day">
                    <div class="card chart-container">
                        <div class="calendar-controls">
                            <!-- <button >◀ ก่อนหน้า</button> -->
                            <button onclick="changeWeekSugar(-1)">◀ ก่อนหน้า</button>
                            <span id="week-label-sugar"></span>
                            <!-- <button >ถัดไป ▶</button> -->
                            <button onclick="changeWeekSugar(1)">ถัดไป ▶</button>
                        </div>
                        <canvas id="SugarChart"></canvas>
                    </div>
                    <div class="card chart-container">
                        <div class="calendar-controls">
                            <!-- <button >◀ ก่อนหน้า</button> -->
                            <button onclick="changeWeekFat(-1)">◀ ก่อนหน้า</button>
                            <span id="week-label-fat"></span>
                            <!-- <button >ถัดไป ▶</button> -->
                            <button onclick="changeWeekFat(1)">ถัดไป ▶</button>
                        </div>
                        <canvas id="FatChart"></canvas>
                    </div>
                </div>
                <!-- กราฟเเถว 3 -->
                <div class="nutrients_day-2">
                    <div class="card chart-container">
                        <div class="calendar-controls">
                            <!-- <button >◀ ก่อนหน้า</button> -->
                            <button onclick="changeWeekSodium(-1)">◀ ก่อนหน้า</button>
                            <span id="week-label-sodium"></span>
                            <!-- <button >ถัดไป ▶</button> -->
                            <button onclick="changeWeekSodium(1)">ถัดไป ▶</button>
                        </div>
                        <canvas id="SodiumChart"></canvas>
                    </div>
                </div>
                <!-- nutrients_day -->
            </div>
            <!-- content -->
        </div>
        <!-- container -->



    <!-- <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <p>Daily_calorie: <?php echo htmlspecialchars($daily_calorie); ?></p>
    <p>BMI: <?php echo htmlspecialchars($bmi); ?></p>
    <p>BMI Status: <?php echo htmlspecialchars($status_bmi); ?></p>
    <p>Diseases: <?php echo htmlspecialchars($diseases); ?></p>
    <p>You have successfully logged in.</p> -->


    <script>
        let currentDate = new Date();
        const username = loggedInUser;

        function getWeekDays(date) {
            let startOfWeek = new Date(date);
            startOfWeek.setDate(date.getDate() - date.getDay());
            let days = [];
            for (let i = 0; i < 7; i++) {
                let day = new Date(startOfWeek);
                day.setDate(startOfWeek.getDate() + i);
                days.push(day);
            }
            return days;
        }

        function formatThaiDate(date) {
            const months = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
            return date.getDate() + " " + months[date.getMonth()];
        }

        // ฟังก์ชันดึงข้อมูลแคลอรี่สำหรับสัปดาห์
        async function getCaloriesForWeek(startDate) {
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);
            const formattedStartDate = startDate.toISOString().split('T')[0];
            const formattedEndDate = endDate.toISOString().split('T')[0];

            try {
                // ส่งคำขอไปยังเซิร์ฟเวอร์เพื่อดึงข้อมูลแคลอรี่
                const response = await fetch('https://flask-api-1-e2yx.onrender.com/get_meal_status_for_month', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: username, startDate: formattedStartDate, endDate: formattedEndDate })
                });

                if (response.ok) {
                    const data = await response.json();
                    const mealStatus = data.mealStatus;
                    return mealStatus;
                } else {
                    console.error('Error fetching meal status:', response.statusText);
                    return {};
                }
            } catch (error) {
                console.error('Error fetching meal status:', error);
                return {};
            }
        }

        // ฟังก์ชันอัปเดตกราฟ
        async function updateChart() {
            let days = getWeekDays(currentDate);
            let labels = days.map(formatThaiDate);
            const mealStatus = await getCaloriesForWeek(days[0]);
            let data = days.map(day => {
                const formattedDate = day.toISOString().split('T')[0];
                return mealStatus[formattedDate] ? mealStatus[formattedDate].total_calories : 0;
            });

            document.getElementById('week-label').textContent = `${labels[0]} - ${labels[6]}`;
            calorieChart.data.labels = labels;
            calorieChart.data.datasets[0].data = data;
            calorieChart.update();
        }

        // ฟังก์ชันเปลี่ยนสัปดาห์
        function changeWeek(direction) {
            currentDate.setDate(currentDate.getDate() + (direction * 7));
            updateChart();
        }

        // ฟังก์ชันสร้างกราฟ
        const ctx = document.getElementById('calorieChart').getContext('2d');
        let calorieChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'เเคลอรี่ (kcal)',
                    data: [],
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderRadius: 10,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // --- Sidebar Toggle JavaScript ---
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.bar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    const icon = sidebarToggle.querySelector('i');
                    if (sidebar.classList.contains('open')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });

                // Close sidebar if clicking outside on mobile/tablet
                document.addEventListener('click', function(event) {
                    if (window.innerWidth <= 992 && sidebar.classList.contains('open')) {
                        const isClickInsideSidebar = sidebar.contains(event.target);
                        const isClickOnToggler = sidebarToggle.contains(event.target);

                        if (!isClickInsideSidebar && !isClickOnToggler) {
                            sidebar.classList.remove('open');
                            const icon = sidebarToggle.querySelector('i');
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }
                });
            }
        });
        // --- End Sidebar Toggle JavaScript ---

        updateChart();
    
// ========================================================================================================================
        let currentDateProtein = new Date();
        let currentDateCarb = new Date();
        let currentDateSugar = new Date();
        let currentDateFat = new Date();
        let currentDateSodium = new Date();


        // ------------------------- โปรตีน -------------------------
        async function getProteinForWeek(startDate) {
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);
            const formattedStartDate = startDate.toISOString().split('T')[0];
            const formattedEndDate = endDate.toISOString().split('T')[0];

            try {
                const response = await fetch('https://flask-api-1-e2yx.onrender.com/get_protein_for_week', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, startDate: formattedStartDate, endDate: formattedEndDate })
                });
                if (response.ok) {
                    const data = await response.json();
                    return data.proteinData;
                }
            } catch (err) {
                console.error("Error fetching protein:", err);
            }
            return {};
        }

        async function updateProteinChart() {
            const days = getWeekDays(currentDateProtein);
            const labels = days.map(formatThaiDate);
            const proteinData = await getProteinForWeek(days[0]);
            const data = days.map(day => {
                const key = day.toISOString().split('T')[0];
                return proteinData[key]?.protein || 0;
            });

            document.getElementById('week-label-protein').textContent = `${labels[0]} - ${labels[6]}`;
            ProteinChart.data.labels = labels;
            ProteinChart.data.datasets[0].data = data;
            ProteinChart.update();
        }

        function changeWeekProtein(direction) {
            currentDateProtein.setDate(currentDateProtein.getDate() + (direction * 7));
            updateProteinChart();
        }

        const ctxProtein = document.getElementById('ProteinChart').getContext('2d');
        const ProteinChart = new Chart(ctxProtein, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'โปรตีน (g)',
                    backgroundColor: '#E94F37',
                    data: [],
                    borderRadius: 10,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        updateProteinChart();

        // ------------------------- คาร์โบไฮเดรต -------------------------
        async function getCarbohydrateForWeek(startDate) {
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);
            const formattedStartDate = startDate.toISOString().split('T')[0];
            const formattedEndDate = endDate.toISOString().split('T')[0];

            try {
                const response = await fetch('https://flask-api-1-e2yx.onrender.com/get_carbohydrate_for_week', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, startDate: formattedStartDate, endDate: formattedEndDate })
                });
                if (response.ok) {
                    const data = await response.json();
                    return data.carbohydrateData;
                }
            } catch (err) {
                console.error("Error fetching carb:", err);
            }
            return {};
        }

        async function updateCarbohydrateChart() {
            const days = getWeekDays(currentDateCarb);
            const labels = days.map(formatThaiDate);
            const carbData = await getCarbohydrateForWeek(days[0]);
            const data = days.map(day => {
                const key = day.toISOString().split('T')[0];
                return carbData[key]?.carbohydrate || 0;
            });

            document.getElementById('week-label-carb').textContent = `${labels[0]} - ${labels[6]}`;
            CarbohydrateChart.data.labels = labels;
            CarbohydrateChart.data.datasets[0].data = data;
            CarbohydrateChart.update();
        }

        function changeWeekCarbohydrate(direction) {
            currentDateCarb.setDate(currentDateCarb.getDate() + (direction * 7));
            updateCarbohydrateChart();
        }

        const ctxCarb = document.getElementById('CarbohydrateChart').getContext('2d');
        const CarbohydrateChart = new Chart(ctxCarb, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'คาร์โบไฮเดรต (g)',
                    backgroundColor: '#FFD166',
                    data: [],
                    borderRadius: 10,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        updateCarbohydrateChart();
        
        // ------------------------- น้ำตาล -------------------------
        async function getSugarForWeek(startDate) {
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);
            const formattedStartDate = startDate.toISOString().split('T')[0];
            const formattedEndDate = endDate.toISOString().split('T')[0];

            try {
                const response = await fetch('https://flask-api-1-e2yx.onrender.com/get_sugar_for_week', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, startDate: formattedStartDate, endDate: formattedEndDate })
                });
                if (response.ok) {
                    const data = await response.json();
                    return data.sugarData;
                }
            } catch (err) {
                console.error("Error fetching sugar:", err);
            }
            return {};
        }

        async function updateSugarChart() {
            const days = getWeekDays(currentDateSugar);
            const labels = days.map(formatThaiDate);
            const sugarData = await getSugarForWeek(days[0]);
            const data = days.map(day => {
                const key = day.toISOString().split('T')[0];
                return sugarData[key]?.sugar || 0;
            });

            document.getElementById('week-label-sugar').textContent = `${labels[0]} - ${labels[6]}`;
            SugarChart.data.labels = labels;
            SugarChart.data.datasets[0].data = data;
            SugarChart.update();
        }

        function changeWeekSugar(direction) {
            currentDateSugar.setDate(currentDateSugar.getDate() + (direction * 7));
            updateSugarChart();
        }

        const ctxSugar = document.getElementById('SugarChart').getContext('2d');
        const SugarChart = new Chart(ctxSugar, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'น้ำตาล (g)',
                    backgroundColor: '#A162E8',
                    data: [],
                    borderRadius: 10,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        updateSugarChart();

        // ------------------------- ไขมัน -------------------------
        async function getFatForWeek(startDate) {
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);
            const formattedStartDate = startDate.toISOString().split('T')[0];
            const formattedEndDate = endDate.toISOString().split('T')[0];

            try {
                const response = await fetch('https://flask-api-1-e2yx.onrender.com/get_fat_for_week', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, startDate: formattedStartDate, endDate: formattedEndDate })
                });
                if (response.ok) {
                    const data = await response.json();
                    return data.fatData;
                }
            } catch (err) {
                console.error("Error fetching fat:", err);
            }
            return {};
        }

        async function updateFatChart() {
            const days = getWeekDays(currentDateFat);
            const labels = days.map(formatThaiDate);
            const fatData = await getFatForWeek(days[0]);
            const data = days.map(day => {
                const key = day.toISOString().split('T')[0];
                return fatData[key]?.fat || 0;
            });

            document.getElementById('week-label-fat').textContent = `${labels[0]} - ${labels[6]}`;
            FatChart.data.labels = labels;
            FatChart.data.datasets[0].data = data;
            FatChart.update();
        }

        function changeWeekFat(direction) {
            currentDateFat.setDate(currentDateFat.getDate() + (direction * 7));
            updateFatChart();
        }

        const ctxFat = document.getElementById('FatChart').getContext('2d');
        const FatChart = new Chart(ctxFat, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'ไขมัน (g)',
                    backgroundColor: '#EF476F',
                    data: [],
                    borderRadius: 10,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        updateFatChart();

        // ------------------------- โซเดียม -------------------------
        async function getSodiumForWeek(startDate) {
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);
            const formattedStartDate = startDate.toISOString().split('T')[0];
            const formattedEndDate = endDate.toISOString().split('T')[0];

            try {
                const response = await fetch('https://flask-api-1-e2yx.onrender.com/get_sodium_for_week', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, startDate: formattedStartDate, endDate: formattedEndDate })
                });
                if (response.ok) {
                    const data = await response.json();
                    return data.sodiumData;
                }
            } catch (err) {
                console.error("Error fetching sodium:", err);
            }
            return {};
        }

        async function updateSodiumChart() {
            const days = getWeekDays(currentDateSodium);
            const labels = days.map(formatThaiDate);
            const sodiumData = await getSodiumForWeek(days[0]);
            const data = days.map(day => {
                const key = day.toISOString().split('T')[0];
                return sodiumData[key]?.sodium || 0;
            });

            document.getElementById('week-label-sodium').textContent = `${labels[0]} - ${labels[6]}`;
            SodiumChart.data.labels = labels;
            SodiumChart.data.datasets[0].data = data;
            SodiumChart.update();
        }

        function changeWeekSodium(direction) {
            currentDateSodium.setDate(currentDateSodium.getDate() + (direction * 7));
            updateSodiumChart();
        }

        const ctxSodium = document.getElementById('SodiumChart').getContext('2d');
        const SodiumChart = new Chart(ctxSodium, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'โซเดียม (mg)',
                    backgroundColor: '#118AB2',
                    data: [],
                    borderRadius: 10,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        updateSodiumChart();

    </script>

</body>
</html>
