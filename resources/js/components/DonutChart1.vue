<!-- src/components/DonutChart.vue -->
<template>
  <div 
    class="relative" 
    :class="chartClass" 
    ref="chartContainer" 
    :aria-label="`Donut chart showing device type breakdown: ${data.map(item => `${item.name} ${item.percentage}%`).join(', ')}`"
  >
    <!-- Total Connections Display -->
    <div class="absolute inset-0 flex items-center justify-center flex-col">
      <span class="text-lg font-bold text-slate-800">{{ formatNumber(total) }}</span>
      <span class="text-xs text-slate-500">Total</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import * as echarts from 'echarts';

const props = defineProps({
  data: {
    type: Array,
    required: true,
    validator: (data) => data.every(item => 'value' in item && 'name' in item && 'percentage' in item && 'itemStyle' in item),
  },
  total: {
    type: Number,
    required: true,
  },
  chartClass: {
    type: String,
    default: 'w-40 h-40',
  },
});

const chartContainer = ref(null);
let myChart = null;

const formatNumber = (num) => {
  return num.toLocaleString();
};

const initChart = () => {
  if (!chartContainer.value) return;
  myChart = echarts.init(chartContainer.value, null, { renderer: 'svg' });

  const option = {
    tooltip: {
      trigger: 'item',
      formatter: '{b}: {c} ({d}%)',
      backgroundColor: 'rgba(255, 255, 255, 0.9)',
      textStyle: { color: '#1f2937', fontSize: 14 },
      borderColor: '#e5e7eb',
      borderWidth: 1,
    },
    legend: {
      show: false, // Legend is handled in the template
    },
    series: [
      {
        name: 'Device Type',
        type: 'pie',
        radius: ['45%', '65%'],
        avoidLabelOverlap: true,
        itemStyle: { borderRadius: 8, borderColor: '#ffffff', borderWidth: 2 },
        label: {
          show: false, // Labels are shown in the external legend
        },
        emphasis: {
          itemStyle: { shadowBlur: 10, shadowOffsetX: 0, shadowColor: 'rgba(0, 0, 0, 0.2)' },
          label: { show: false },
        },
        labelLine: { show: false },
        data: props.data,
      },
    ],
    textStyle: { color: '#1f2937' },
  };

  myChart.setOption(option);
};

const resizeChart = () => {
  if (myChart) myChart.resize();
};

watch(() => props.data, () => {
  initChart();
}, { deep: true });

onMounted(() => {
  initChart();
  window.addEventListener('resize', resizeChart);
});

onUnmounted(() => {
  if (myChart) myChart.dispose();
  window.removeEventListener('resize', resizeChart);
});
</script>