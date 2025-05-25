<!-- src/components/DonutChart.vue -->
<template>
  <div class="w-full h-64" ref="chartContainer" :aria-label="`Donut chart showing ${title}: ${malePercent}% Male, ${femalePercent}% Female`"></div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import * as echarts from 'echarts';

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  maleValue: {
    type: Number,
    required: true,
  },
  femaleValue: {
    type: Number,
    required: true,
  },
});

const chartContainer = ref(null);
let myChart = null;

// Compute percentages
const malePercent = computed(() => {
  const total = props.maleValue + props.femaleValue;
  return total ? Math.round((props.maleValue / total) * 100) : 0;
});

const femalePercent = computed(() => {
  const total = props.maleValue + props.femaleValue;
  return total ? Math.round((props.femaleValue / total) * 100) : 0;
});

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
      bottom: '0',
      left: 'center',
      textStyle: { color: '#1f2937', fontSize: 12, fontWeight: '500' },
      itemGap: 15,
    },
    series: [
      {
        name: props.title,
        type: 'pie',
        radius: ['45%', '65%'],
        avoidLabelOverlap: true,
        itemStyle: { borderRadius: 8, borderColor: '#ffffff', borderWidth: 2 },
        label: {
          show: true,
          position: 'outside',
          formatter: '{b}: {d}%',
          color: '#1f2937',
          fontSize: 12,
          fontWeight: '500',
        },
        emphasis: {
          itemStyle: { shadowBlur: 10, shadowOffsetX: 0, shadowColor: 'rgba(0, 0, 0, 0.2)' },
          label: { show: true, fontSize: 16, fontWeight: 'bold', color: '#1f2937' },
        },
        labelLine: { show: true, length: 10, length2: 15, smooth: 0.2 },
        data: [
          { value: props.maleValue, name: 'Male', itemStyle: { color: '#6366f1' } },
          { value: props.femaleValue, name: 'Female', itemStyle: { color: '#f97316' } },
        ],
      },
    ],
    textStyle: { color: '#1f2937' },
  };

  myChart.setOption(option);
};

const resizeChart = () => {
  if (myChart) myChart.resize();
};

// Watch for data changes
watch([() => props.maleValue, () => props.femaleValue], () => {
  initChart();
});

onMounted(() => {
  initChart();
  window.addEventListener('resize', resizeChart);
});

onUnmounted(() => {
  if (myChart) myChart.dispose();
  window.removeEventListener('resize', resizeChart);
});
</script>