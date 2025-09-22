import './globals/theme.js';
import './globals/modals.js';
import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

import Chart from 'chart.js/auto';
window.Chart = Chart;

Livewire.start()
