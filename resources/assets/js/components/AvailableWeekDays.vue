<template>
    <div class="row">
        <div class="col-md-12" v-for="day in this.weekdays" :key="day.id">
            <input type="checkbox" name="week_days[]" :value="day.id" v-on:input="updateValue($event)" />
            <label>{{ day.name }}</label>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["weekdays"],
        methods: {
            updateValue: function ($event) {
                 this.$emit('input', $event);
            }
        },
        mounted() {
        }
    }
</script>