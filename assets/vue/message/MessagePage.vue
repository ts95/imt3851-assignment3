<script>
    import MessageRow from './MessageRow.vue';

    export default {
        name: 'MessagePage',
        components: {
            MessageRow,
        },
        props: {
            auth: {
                required: true,
            },
        },
        data() {
            return {
                intervalId: null,
                messages: [],
                exchanges: [],
                selectedExchangeId: this.$route.params.id ? this.$route.params.id : null,
            };
        },
        async asyncData() {
            let res1 = await this.$http.get('/a/message/exchange');

            let data = {};

            if (res1.data.ok) {
                data.exchanges = res1.data.exchanges;

                if (this.selectedExchangeId == null && data.exchanges.length > 0) {
                    data.selectedExchangeId = data.exchanges[0].exchange_id;
                } else {
                    let res = await this.$http.get(`/a/message/exchange/${this.selectedExchangeId}`);

                    if (res.ok) {
                        this.messages = res.data.messages;
                    }
                }
            }

            return data;
        },
        ready() {
            this.intervalId = setInterval(this.reloadAsyncData, 2000);
        },
        beforeDestroy() {
            clearInterval(this.intervalId);
        },
        watch: {
            async selectedExchangeId(val, oldVal) {
                if (!val)
                    return;

                this.messages = [];

                let res = await this.$http.get(`/a/message/exchange/${val}`);

                if (res.ok) {
                    this.messages = res.data.messages;
                }
            },
            messages(val, oldVal) {
                let messageNode = this.$el.querySelector('.message:last-child');

                if (messageNode)
                    messageNode.scrollIntoView();
            },
        },
        methods: {
            async sendMessage(e) {
                if (!e.shiftKey) {
                    let message = e.target.value;
                    e.target.value = '';

                    if (message) {
                        let res = await this.$http.post('/a/message/send', {
                            message: message,
                            exchangeId: this.selectedExchangeId,
                        });

                        this.reloadAsyncData();
                    }
                }
            },
            selectExchange(id) {
                this.selectedExchangeId = id;
            },
        },
    };
</script>

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="exchanges">
                    <p v-if="exchanges.length === 0" class="text-muted">You haven't sent or received any messages yet</p>
                    <div v-for="exchange in exchanges" @click="selectExchange(exchange.id)" :class="selectedExchangeId == exchange.id ? 'exchange active' : 'exchange'">
                        <p>{{ exchange.members.join(', ') }}</p>
                        <template v-if="exchange.message.name">
                            <a href="#">{{ exchange.message.name }}</a>:&nbsp;<span>{{ exchange.message.message }}</span>
                        </template>
                        <span class="text-muted" v-else>New exchange</span>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="messages">
                    <message-row v-for="message in messages" class="message" :message="message"></message-row>
                </div>
                <textarea class="form-control form-message" placeholder="Enter message a message here..." rows="2" @keyup.enter="sendMessage($event)" maxlength="2000" :disabled="selectedExchangeId == null"></textarea>
            </div>
        </div>
    </div>
</template>

<style lang="sass">
    .exchanges {
        height: 70vh;
        overflow-y: scroll;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 3px;

        .exchange {
            cursor: pointer;
            padding: 20px;
            border-bottom: 1px solid #ccc;
        }

        .exchange.active {
            border: 1px solid #ccc;
            background-color: #f8f8f8;
        }

        .exchange:hover {
            background-color: #f8f8f8;
        }
    }

    .messages {
        height: 60vh;
        overflow-y: scroll;
    }
</style>
