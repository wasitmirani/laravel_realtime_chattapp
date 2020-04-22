<template>
  <div class="container">
    <h3 class="text-center">Messaging</h3>
    <div class="messaging">
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Recent</h4>
            </div>
            <div class="srch_bar">
              <div class="stylish-input-group">
                <input type="text" class="search-bar" placeholder="Search" />
                <span class="input-group-addon">
                  <button type="button">
                    <i class="fa fa-search" aria-hidden="true"></i>
                  </button>
                </span>
              </div>
            </div>
          </div>
          <div class="inbox_chat">
            <!-- active_chat -->
            <div class="chat_list" v-for="user in users" :key="user.id">
              <div class="chat_people">
                <div class="chat_img">
                  <img
                    src="https://ptetutorials.com/images/user-profile.png"
                    alt="sunil"
                    @click="message_conversation(user)"
                  />
                </div>
                <div class="chat_ib">
                  <a href="#" @click="message_conversation(user)">
                    <h5>
                      {{ user.name }}
                      <!-- <span class="chat_date">Dec 25</span> -->
                    </h5>
                  </a>
                  <p>
                    <!--  -->
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="mesgs">
          <div class="msg_history">
            <div v-for="chat in chat_list" :key="chat.id">
              <div
                v-if="
                                    chat.send_from == receiver_user.id &&
                                        receiver_id == auth_user.id
                                "
              >
                <div class="incoming_msg">
                  <div class="incoming_msg_img">
                    <img src="https://ptetutorials.com/images/user-profile.png" />
                  </div>
                  <div class="received_msg">
                    <div class="received_withd_msg">
                      <p>{{ chat.message }}</p>

                      <span class="time_date">
                        11:01 AM |
                        {{ chat.receiver_id }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="outgoing_msg" v-if="chat.send_from == auth_user.id">
                <div class="sent_msg">
                  <p>{{ chat.message }}</p>
                  <span class="time_date">11:01 AM | {{ chat.sender_id }}</span>
                </div>
              </div>
            </div>
          </div>
          <div></div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input
                type="text"
                v-model="message_body"
                class="form-control"
                placeholder="Type a message"
                v-on:keyup.enter="send_message"
              />
              <button class="msg_send_btn" type="button" @click="send_message">
                <i class="fas fa-paper-plane" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- <p class="text-center top_spac">
        Design by
        <a target="_blank" href="#">Sunil Rajput</a>
      </p>-->
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      auth_user: {},
      users: {},
      receiver_user: {},
      message_body: "",
      typing: false,
      chat_list: [],
      ready: false,
      info: [],
      connectionCount: 0,
      sender_id: "",
      receiver_id: "",

      online: []
    };
  },
  methods: {
    //Send Messages Using Socket.io and apis for store in db

    send_message() {
      //socket sending data

      socket.emit(
        "chat-message",
        {
          message: this.message_body,
          sender_id: this.auth_user.id,
          send_from: this.auth_user.id,
          receiver_id: this.receiver_user.id,
          user: this.auth_user.name
        },
        this.auth_user.name
      );
      //now push message on array of chat list
      if (this.auth_user.id == this.receiver_id) {
        this.chat_list.push({
          message: this.message_body,
          sender_id: this.auth_user.id,
          sender_from: this.auth_user.id,
          receiver_id: this.receiver_user.id
        });
      }

      // now message store in your database

      let frmdata = new FormData();
      frmdata.append("sender_id", this.auth_user.id);
      frmdata.append("receiver_id", this.receiver_user.id);
      frmdata.append("send_from", this.auth_user.id);
      frmdata.append("message", this.message_body);

      //now call post api for send data in db

      axios
        .post(this.$host_url + "send/message", frmdata, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(res => {
          console.log(res.data);

          //call the chat history both user from database
          //this is send_id =  this.auth_user.id +
          //receiver_user=this. receiver user id sending api

          if (this.auth_user.id == this.receiver_id) {
            axios
              .get(
                this.$host_url +
                  "chat/list/" +
                  this.auth_user.id +
                  "/" +
                  this.receiver_user.id,
                {
                  headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                  }
                }
              )
              .then(res => {
                // let chat_list = res.data;
                (this.message_body = ""),
                  // this.receiver_id= user.id;

                  (this.chat_list = res.data);
              });
          }
          console.log("sended success");
        });
    },
    //get message conversation of user

    message_conversation(user) {
      axios
        .get(
          this.$host_url + "chat/list/" + this.auth_user.id + "/" + user.id,
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          }
        )
        .then(res => {
          this.chat_list = res.data;
          this.receiver_user = user;
          this.receiver_id = this.auth_user.id;
          console.log("chat data " + res.data);
          console.log("recv Succes");
        });
    },

    //get User List from databse
    user_list() {
      axios
        .get(this.$host_url + "users/list/" + localStorage.getItem("user_id"), {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(res => {
          this.users = res.data;
        });
    },
    //now get Auth User information  From database
    auth_user_data() {
      axios
        .get(this.$host_url + "auth/user/" + localStorage.getItem("user_id"), {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(res => {
          this.auth_user = res.data;
        });
    }
    // send() {
    //     socket.emit(
    //         "chat-message",
    //         {
    //             message: this.messsage_body,
    //             // send_from: this.auth_user.id
    //             sender_id: this.auth_user.id,
    //             send_from: this.auth_user.id,
    //             receiver_id: this.receiver_user.id
    //         },
    //         this.auth_user.name
    //     );
    //     message = null;
    // }
  },
  mounted() {
    //call this methods
    this.auth_user_data();
    this.user_list();
    socket.on("chat-message", data => {
      console.log("socket data" + data);
      if (this.auth_user.id == data.receiver_id) {
        this.chat_list.push({
          message: data.message,
          // send_from: data.send_from
          sender_id: data.sender_id,
          send_from: data.send_from,
          receiver_id: data.receiver_id
          // type: 1,
          // by: data.user
        });
      }

      this.sender_id = data.sender_id;
      this.receiver_id = data.receiver_id;

      console.log("sock rec=" + this.receiver_id);
      // this.typing = false;
    });
  }
};
</script>

<style>
.container {
  max-width: 1170px;
  margin: auto;
}
img {
  max-width: 100%;
}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%;
  border-right: 1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac {
  margin: 20px 0 0;
}

.recent_heading {
  float: left;
  width: 40%;
}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%;
}
.headind_srch {
  padding: 10px 29px 10px 20px;
  overflow: hidden;
  border-bottom: 1px solid #c4c4c4;
}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input {
  border: 1px solid #cdcdcd;
  border-width: 0 0 1px 0;
  width: 80%;
  padding: 2px 0 4px 6px;
  background: none;
}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon {
  margin: 0 0 0 -27px;
}

.chat_ib h5 {
  font-size: 15px;
  color: #464646;
  margin: 0 0 8px 0;
}
.chat_ib h5 span {
  font-size: 13px;
  float: right;
}
.chat_ib p {
  font-size: 14px;
  color: #989898;
  margin: auto;
}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people {
  overflow: hidden;
  clear: both;
}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat {
  height: 550px;
  overflow-y: scroll;
}

.active_chat {
  background: #ebebeb;
}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
}
.received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg {
  width: 57%;
}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 60%;
}

.sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0;
  color: #fff;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.outgoing_msg {
  overflow: hidden;
  margin: 26px 0 26px;
}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {
  border-top: 1px solid #c4c4c4;
  position: relative;
}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging {
  padding: 0 0 50px 0;
}
.msg_history {
  height: 516px;
  overflow-y: auto;
}
</style>
