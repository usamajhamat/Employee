<template>
    <!-- <pre>{{ companies }}</pre> -->
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-8 py-6 rounded-t-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center mr-4">
                                <BarChart3 class="h-6 w-6 text-white" aria-hidden="true" />
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Weekly Company Reports</h1>
                                <p class="text-teal-100 mt-1">Day-wise recruitment analytics (Monday - Friday)</p>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                            <!-- <button
                                @click="downloadPDF"
                                class="inline-flex items-center px-4 py-2 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200"
                                aria-label="Download PDF report"
                            >
                                <Download class="h-4 w-4 mr-2" aria-hidden="true" />
                                Download PDF
                            </button> -->
                            <button
                                @click="refreshData"
                                :disabled="isLoading"
                                class="inline-flex items-center px-4 py-2 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200"
                                aria-label="Refresh data"
                            >
                                <RotateCcw
                                    class="h-4 w-4 mr-2"
                                    :class="{ 'animate-spin': isLoading }"
                                    aria-hidden="true"
                                />
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="week-select" class="block text-sm font-semibold text-gray-700 mb-2"
                            >Select Week</label
                        >
                        <input
                            id="week-select"
                            v-model="selectedWeek"
                            type="week"
                            @change="updateWeek"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                            aria-describedby="week-select-help"
                        />
                        <p id="week-select-help" class="text-xs text-gray-500 mt-1">
                            Select a week to filter the data
                        </p>
                    </div>
                    <!-- <div class="flex items-end">
                        <button
                            @click="resetFilters"
                            class="w-full px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                            aria-label="Reset filters"
                        >
                            Reset Filters
                        </button>
                    </div> -->
                </div>
            </div>

            <!-- Bar Charts Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 p-8" data-section="bar">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                    <h3 class="text-xl font-semibold text-gray-900">Daily Performance (Monday - Friday)</h3>
                    <div class="mt-4 sm:mt-0 flex flex-wrap items-center space-x-3"></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
                    <div v-for="company in filteredCompanies" :key="`bar-${company.name}`" class="text-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ company.name }}</h4>
                        <div class="relative h-64 mb-4">
                            <canvas :ref="(el) => setCanvasRef(el, company.name, 'bar')"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Charts Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 p-8" v-if="filteredCompanies?.length > 0" data-section="pie">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-semibold text-gray-900">Weekly Distribution Overview</h3>
                    <PieChart class="h-6 w-6 text-gray-400" aria-hidden="true" />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
                    <div v-for="company in filteredCompanies" :key="`pie-${company.name}`" class="text-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ company.name }}</h4>
                        <div class="relative h-64 mb-4">
                            <canvas :ref="(el) => setCanvasRef(el, company.name, 'pie')"></canvas>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                                    <span class="text-gray-600">Interviewed</span>
                                </div>
                                <span class="font-semibold">{{ company.totals.interviewed }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                                    <span class="text-gray-600">Passed</span>
                                </div>
                                <span class="font-semibold">{{ company.totals.passed }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-orange-500 rounded mr-2"></div>
                                    <span class="text-gray-600">Hostel</span>
                                </div>
                                <span class="font-semibold">{{ company.totals.hostel }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded mr-2"></div>
                                    <span class="text-gray-600">Walk-in</span>
                                </div>
                                <span class="font-semibold">{{ company.totals.walkin }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                    <div>
                        <label for="checked-by" class="block text-sm font-semibold text-gray-700 mb-2"
                            >Checked By</label
                        >
                        <div class="border-b-2 border-gray-300 pb-2">
                            <input
                                id="checked-by"
                                v-model="approvalData.checkedBy"
                                type="text"
                                class="w-full bg-transparent focus:outline-none text-gray-800"
                                placeholder="Enter name"
                                aria-describedby="checked-by-help"
                            />
                            <p id="checked-by-help" class="sr-only">
                                Enter the name of the person who checked the report
                            </p>
                        </div>
                    </div>
                    <div>
                        <label for="approved-by" class="block text-sm font-semibold text-gray-700 mb-2"
                            >Approved By</label
                        >
                        <div class="border-b-2 border-gray-300 pb-2">
                            <input
                                id="approved-by"
                                v-model="approvalData.approvedBy"
                                type="text"
                                class="w-full bg-transparent focus:outline-none text-gray-800"
                                placeholder="Enter name"
                                aria-describedby="approved-by-help"
                            />
                            <p id="approved-by-help" class="sr-only">
                                Enter the name of the person who approved the report
                            </p>
                        </div>
                    </div>
                    <div>
                        <label for="date-from" class="block text-sm font-semibold text-gray-700 mb-2"
                            >Date From</label
                        >
                        <div class="border-b-2 border-gray-300 pb-2">
                            <input
                                id="date-from"
                                v-model="approvalData.dateFrom"
                                type="date"
                                class="w-full bg-transparent focus:outline-none text-gray-800"
                                aria-describedby="date-from-help"
                            />
                            <p id="date-from-help" class="sr-only">
                                Select the start date of the report
                            </p>
                        </div>
                    </div>
                    <div>
                        <label for="date-to" class="block text-sm font-semibold text-gray-700 mb-2"
                            >To</label
                        >
                        <div class="border-b-2 border-gray-300 pb-2">
                            <input
                                id="date-to"
                                v-model="approvalData.dateTo"
                                type="date"
                                class="w-full bg-transparent focus:outline-none text-gray-800"
                                aria-describedby="date-to-help"
                            />
                            <p id="date-to-help" class="sr-only">
                                Select the end date of the report
                            </p>
                        </div>
                    </div>
                    <div>
                        <label for="week" class="block text-sm font-semibold text-gray-700 mb-2"
                            >Week</label
                        >
                        <div class="border-b-2 border-gray-300 pb-2">
                            <input
                                id="week"
                                v-model="approvalData.week"
                                type="text"
                                class="w-full bg-transparent focus:outline-none text-gray-800"
                                placeholder="Week number"
                                aria-describedby="week-help"
                            />
                            <p id="week-help" class="sr-only">
                                Enter the week number of the report
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, nextTick } from "vue";
import { BarChart3, Download, PieChart, RotateCcw } from "lucide-vue-next";
import Chart from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";
import html2pdf from "html2pdf.js";
import { useStore } from "vuex";
import { FETCH_WEEKLY_REPORTS } from "@/services/store/actions.type";

// Register plugin
Chart.register(ChartDataLabels);

// Store and state
const store = useStore();
const isLoading = ref(false);
const selectedWeek = ref("");
const canvasRefs = ref({});
const chartInstances = reactive({});

// Computed from Vuex
const companies = computed(() => store.getters["weeklyInfo/weeklyReports"]);

// Fetch reports
const fetchWeeklyReports = () => {
    store.dispatch("weeklyInfo/" + FETCH_WEEKLY_REPORTS, {
        week: selectedWeek.value,
    });
};

// Approval info
const approvalData = reactive({
    checkedBy: "",
    approvedBy: "",
    dateFrom: "",
    dateTo: "",
    week: "",
});

companies?.value?.forEach((company) => {
    const totals = {
        total: 0,
        interviewed: 0,
        passed: 0,
        hostel: 0,
        walkin: 0,
    };
    Object.values(company.dailyData).forEach((day) => {
        totals.total += day.total;
        totals.interviewed += day.interviewed;
        totals.passed += day.passed;
        totals.hostel += day.hostel;
        totals.walkin += day.walkin;
    });
    company.totals = totals;
});

const weekDays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

const filteredCompanies = computed(() => companies.value);

const setCanvasRef = (el, companyName, type) => {
    if (el) {
        canvasRefs.value[`${type}-${companyName}`] = el;
    }
};

const createBarChart = (company) => {
    const canvas = canvasRefs.value[`bar-${company.name}`];
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    if (chartInstances[`bar-${company.name}`]) {
        chartInstances[`bar-${company.name}`].destroy();
    }

    chartInstances[`bar-${company.name}`] = new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["M", "T", "W", "T", "F"],
            datasets: [
                {
                    label: "Walk-in",
                    data: weekDays.map((d) => company.dailyData[d].walkin),
                    backgroundColor: "#a78bfa",
                    borderRadius: 2,
                },
                {
                    label: "Hostel",
                    data: weekDays.map((d) => company.dailyData[d].hostel),
                    backgroundColor: "#f59e0b",
                    borderRadius: 2,
                },
                {
                    label: "Passed",
                    data: weekDays.map((d) => company.dailyData[d].passed),
                    backgroundColor: "#10b981",
                    borderRadius: 2,
                },
                {
                    label: "Interviewed",
                    data: weekDays.map((d) => company.dailyData[d].interviewed),
                    backgroundColor: "#3b82f6",
                    borderRadius: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { mode: "index", intersect: false },
                datalabels: {
                    display: (ctx) => ctx.dataset.data[ctx.dataIndex] > 0,
                    color: "#fff",
                    font: { weight: "bold", size: 11 },
                    formatter: (v) => v,
                },
            },
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: "#f3f4f6" },
                },
            },
        },
    });
};

const createPieChart = (company) => {
    const canvas = canvasRefs.value[`pie-${company.name}`];
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    if (chartInstances[`pie-${company.name}`]) {
        chartInstances[`pie-${company.name}`].destroy();
    }

    const data = [
        company.totals.interviewed,
        company.totals.passed,
        company.totals.hostel,
        company.totals.walkin,
    ];

    chartInstances[`pie-${company.name}`] = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["Interviewed", "Passed", "Hostel", "Walk-in"],
            datasets: [
                {
                    data,
                    backgroundColor: [
                        "#3b82f6",
                        "#10b981",
                        "#f59e0b",
                        "#a78bfa",
                    ],
                    borderWidth: 2,
                    borderColor: "#ffffff",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label(context) {
                            const total = context.dataset.data.reduce(
                                (a, b) => a + b,
                                0
                            );
                            const percentage = (
                                (context.parsed / total) *
                                100
                            ).toFixed(1);
                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                        },
                    },
                },
                datalabels: {
                    display: (ctx) => ctx.dataset.data[ctx.dataIndex] > 0,
                    color: "#ffffff",
                    font: { weight: "bold", size: 12 },
                    formatter(value, ctx) {
                        const total = ctx.dataset.data.reduce(
                            (a, b) => a + b,
                            0
                        );
                        const percent = ((value / total) * 100).toFixed(0);
                        return `${value}\n(${percent}%)`;
                    },
                },
            },
            cutout: "50%",
        },
    });
};

const destroyAllCharts = () => {
    Object.keys(chartInstances).forEach((key) => {
        if (chartInstances[key]) {
            chartInstances[key].destroy();
            delete chartInstances[key];
        }
    });
};

const initializeCharts = async () => {
    await nextTick();
    filteredCompanies?.value?.forEach((company) => {
        createBarChart(company);
        createPieChart(company);
    });
};

const getCurrentWeek = () => {
    const today = new Date();
    const firstDayOfYear = new Date(today.getFullYear(), 0, 1);
    const pastDaysOfYear = (today - firstDayOfYear) / 86400000;
    const weekNumber = Math.ceil(
        (pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7
    );
    return `${today.getFullYear()}-W${weekNumber.toString().padStart(2, "0")}`;
};

const updateWeek = async () => {
    isLoading.value = true;
    try {
        await fetchWeeklyReports();
        await initializeCharts();
        const weekNumber = selectedWeek.value.split("-W")[1];
        approvalData.week = `Week ${weekNumber}`;
    } catch (err) {
        console.error("Error updating week data:", err);
        alert("Failed to update week data.");
    } finally {
        isLoading.value = false;
    }
};

const refreshData = async () => {
    isLoading.value = true;
    try {
        await fetchWeeklyReports();
        await initializeCharts();
        alert("Data refreshed successfully!");
    } catch (err) {
        console.error("Error refreshing data:", err);
        alert("Failed to refresh data.");
    } finally {
        isLoading.value = false;
    }
};

const downloadPDF = async () => {
    try {
        const element = document.querySelector(".min-h-screen");
        const opt = {
            margin: 0.5,
            filename: `weekly-company-report-${selectedWeek.value}.pdf`,
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
        };
        await html2pdf().set(opt).from(element).save();
        alert("PDF downloaded successfully!");
    } catch (err) {
        console.error("Error generating PDF:", err);
        alert("Failed to download PDF.");
    }
};

const resetFilters = () => {
    selectedWeek.value = getCurrentWeek();
    updateWeek();
};

// Mount lifecycle
onMounted(() => {
    selectedWeek.value = getCurrentWeek();
    const today = new Date();
    const weekStart = new Date(
        today.setDate(today.getDate() - today.getDay() + 1)
    );
    const weekEnd = new Date(weekStart);
    weekEnd.setDate(weekStart.getDate() + 4);
    approvalData.dateFrom = weekStart.toISOString().split("T")[0];
    approvalData.dateTo = weekEnd.toISOString().split("T")[0];
    approvalData.week = `Week ${selectedWeek.value.split("-W")[1]}`;
    fetchWeeklyReports();
    initializeCharts();
});
</script>

<style scoped>
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    border: 0;
}
</style>