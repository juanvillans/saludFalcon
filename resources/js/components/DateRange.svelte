<script>
    import { DatePicker } from '@svelte-plugins/datepicker';
    import { format } from 'date-fns';
    import { createEventDispatcher } from "svelte";
    const dispatch = createEventDispatcher();

    const today = new Date();
    
    const MILLISECONDS_IN_DAY = 24 * 60 * 60 * 1000;
  
    const getDateFromToday = (days) => {
      console.log(Date.now());
      
      return Date.now() - days * MILLISECONDS_IN_DAY;
    };
  
    let startDate = getDateFromToday(29);
    let endDate = today;
    let dateFormat = 'MMM d, yyyy';
    let isOpen = false;
  
    let formattedStartDate = '';
  
    const onClearDates = () => {
      startDate = '';
      endDate = '';
    };
  
    const toggleDatePicker = () => (isOpen = !isOpen);
  
    const formatDate = (dateString) => {
      if (isNaN(new Date(dateString))) {
        return '';
      }
  
      return dateString && format(new Date(dateString), dateFormat) || '';
    };
  
    $: formattedStartDate = formatDate(startDate);
    $: formattedEndDate = formatDate(endDate);
    
    const onDateChange = (args) => {
      console.log(args.startDate, args.endDate);
      
      dispatch("changeDateFilter", {startDate:args.startDate, endDate:args.endDate});
  };

  // Reactive statement to detect changes in startDate or endDate
  
  </script>
  
  <div class="date-filter">
    <DatePicker {onDateChange}
    presetLabels={[
      "Hoy",
      "Últimos 7 Días",
      "Últimos 30 Días",
      "Últimos 60 Días",
      "Últimos 90 Días",
      "Último Año"
    ]}
    startOfWeek=1
    dowLabels={["dom","lun", "mar", "mié", "jue", "vie", "sáb"]} monthLabels={[
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre"
    ]} bind:isOpen bind:startDate bind:endDate isRange showPresets>
      <div class="date-field" on:click={toggleDatePicker} class:open={isOpen}>
        <i class="icon-calendar" />
        <div class="date">
          {#if startDate}
            {formattedStartDate} - {formattedEndDate}
          {:else}
            Pick a date
          {/if}
        </div>
        {#if startDate}
          <span on:click={onClearDates}>
            <i class="os-icon-x" />
          </span>
        {/if}
      </div>
    </DatePicker>
  </div>
  
  <style>
    .date-field {
      align-items: center;
      background-color: #fff;
      border-bottom: 1px solid #e8e9ea;
      display: inline-flex;
      gap: 8px;
      min-width: 100px;
      padding: 16px;
    }
  
    .date-field.open {
      border-bottom: 1px solid #0087ff;
    }
  
    .date-field .icon-calendar {
      background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAEmSURBVHgB7ZcPzcIwEMUfXz4BSCgKwAGgACRMAg6YBBxsOMABOAAHFAXgAK5Z2Y6lHbfQ8SfpL3lZaY/1rb01N+BHUKSMNBfEJjZWISA56Uo6C2KvVpkgFn9oRx9vICFtUT1JKO3tvRtZdjBxXQs+YY+1FenIfuesPUGVVLzfRWKvmrSzbbN19wS+kAb2+sCEuUxrYzkbe4YvCVM2Vr5NPAkVa+van7Wn38U95uTpN5TJ/A8ZKemAakmbmJJGpI0gVmwA0huieFItjG19DgTHtwIZhCfZq3ztCuzQYh+FKBSvusjAGs8PnLYkLgMf34JoIBqIBqKBaIAb0Kw9RlhMCTbzzPWAqYq7LsuPaGDUsYmznaOk5zChUJTNQ4TFVMkrOL4HPsoNn26PxROHCggAAAAASUVORK5CYII=) no-repeat center center;
      background-size: 14px 14px;
      height: 14px;
      width: 14px;
    }
  </style>