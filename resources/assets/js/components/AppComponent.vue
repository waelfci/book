<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Book settings</div>

                    <div class="card-body">
                        <div class="alert alert-danger" v-if="this.response_error!=''" v-html="this.response_error"></div>

                        <form method="post" action="#" v-on:submit.prevent="submit">
                            <div class="form-group">
                                <label>Number of chapters</label>
                                <input type="number" required name="number_of_chapters" id="number_of_chapters" v-model="number_of_chapters" />
                            </div>
                            <div class="form-group">
                                <label>Available week days</label>
                                <AvailableWeekDays v-bind:weekdays="week_days" @input="updateSelectedDays"></AvailableWeekDays>
                            </div>
                            <div class="form-group">
                                <label>Sessions</label>
                                <input type="number" required name="sessions" id="sessions" v-model="sessions" />
                            </div>
                            <div class="form-group">
                                <label>Start date of reading</label>
                                <input type="date" required name="start_date" id="start_date" v-model="start_date" />
                            </div>

                            <input type="submit" value="Submit" class="btn btn-primary" />
                        </form>

                        <div class="row" v-if="this.response.length > 0">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Chapter name</th>
                                        <th>Available dates</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in this.response" :key="item.id">
                                        <td>{{item.chapter}}</td>
                                        <td>
                                            <div v-for="session in item.sessions" :key="session">
                                                {{ session }}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AvailableWeekDays from './AvailableWeekDays';
    import axios from 'axios';

    export default {
        components: {
            AvailableWeekDays
        },
        data() {
          return {
              week_days: [],
              number_of_chapters: 0,
              sessions: 0,
              start_date: '',
              selected_days: [],
              response_error: '',
              response: []
          }
        },
        methods: {
            updateSelectedDays($event)
            {
                if($event.target.checked) {
                    if(!this.selected_days.includes($event.target.value)) {
                        this.selected_days.push($event.target.value);
                    }
                } else {
                    if(this.selected_days.includes($event.target.value)) {

                        let filtered = this.selected_days.filter(val => val != $event.target.value);

                        this.selected_days = filtered;
                    }
                }
            },
              submit(e) {
                let self = this;

                self.response_error = '';

                if(this.number_of_chapters <= 0) {
                    self.response_error += '<p>Enter number of chapters</p>';
                }

                  if(this.sessions <= 0) {
                      self.response_error += '<p>Enter number of sessions</p>';
                  }

                  if(this.start_date == '') {
                      self.response_error += '<p>Enter start date</p>';
                  }

                  if(this.selected_days.length == 0) {
                      self.response_error += '<p>Choose days of week</p>';
                  }

                  if(this.selected_days.length >0 && this.sessions > 0 && this.selected_days.length < this.sessions) {
                      self.response_error += '<p>Number of available week days must be greater than or equal sessions</p>';
                  }

                  if(self.response_error != '') {
                    return false;
                  }

                let payload = {
                    number_of_chapters: this.number_of_chapters,
                    sessions: this.sessions,
                    start_date: this.start_date,
                    week_days: this.selected_days
                };

                axios.post(window.base_url + "/api/save", payload).then(response => {

                    self.response = response.data.data;
                    self.chapters(response.data.data);
                })
              },
            chapters(response) {


                let arr = [];

                for(let i in response) {

                    arr.push({
                       id: i,
                       chapter: 'Chapter ' + i,
                       sessions: response[i]['sessions']
                    });
                }

                this.response = arr;

                console.log(this.response);
            }
        },
        mounted() {

            let self = this;

            axios.get(window.base_url + "/api/week-days").then(response => {
                self.week_days = response.data.days;
            });
        }
    }
</script>
