<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Login</div>

          <div class="card-body">
            <form @submit="login_user" method="post">
              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                <div class="col-md-6">
                  <input
                    id="email"
                    type="email"
                    class="form-control"
                    name="email"
                    v-model="email"
                    value
                    required
                    autocomplete="email"
                    autofocus
                  />
                </div>
              </div>

              <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                <div class="col-md-6">
                  <input
                    id="password"
                    type="password"
                    v-model="password"
                    class="form-control"
                    name="password"
                    required
                    autocomplete="current-password"
                  />
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6 offset-md-4">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                    <label class="form-check-label" for="remember">Remember Me</label>
                  </div>
                </div>
              </div>

              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-primary">Login</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: ""
    };
  },
  methods: {
    login_user(evt) {
      console.log("login click");
      evt.preventDefault();
      let frm_data = new FormData();
      frm_data.append("email", this.email); //v-model
      frm_data.append("password", this.password); //v-model password
      let config = {
        header: {
          "Content-Type": "multipart/form-data"
        }
      };
      axios.post(this.$host_url + "login", frm_data, config).then(res => {
        console.log(res.data);
        let token = res.data.token.token;
        let user_id = res.data.user.id;
        console.log(user_id);
        if (res.data.status == true) {
          localStorage.setItem("token", token);
          localStorage.setItem("user_id", user_id);
          window.location.href = "http://localhost/chat_app/home#/home";
        }
      });
    }
  },
  mounted() {
    console.log("Login Component");
  }
};
</script>

<style></style>
