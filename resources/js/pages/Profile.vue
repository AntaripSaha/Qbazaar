<template>
  <section class="popular-items section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 mb-2">
          <ul class="nav nav-pills profile-pills nav-fill">
            <li class="nav-item">
              <a
                v-bind:class="[
                  activeTab == 'profile' ? 'active' : '',
                  'nav-link',
                ]"
                data-toggle="tab"
                href="#profile"
                role="tab"
                @click="changeTab('profile')"
                aria-controls="profile"
                aria-selected="true"
                ><i class="icofont-ui-user"></i>
                <span class="d-none d-sm-inline">{{
                  $lang["Profile Overview"]
                }}</span></a
              >
            </li>
            <li v-if="user.user_type == 'customer'" class="nav-item">
              <a
                v-bind:class="[
                  activeTab == 'orders' ? 'active' : '',
                  'nav-link',
                ]"
                data-toggle="tab"
                href="#orders"
                role="tab"
                @click="changeTab('orders')"
                aria-controls="orders"
                aria-selected="false"
                ><i class="icofont-listine-dots"></i>
                <span class="d-none d-sm-inline">{{
                  $lang["My Orders"]
                }}</span></a
              >
            </li>
            <li v-if="user.user_type == 'delivery_boy'" class="nav-item">
              <a
                v-bind:class="[
                  activeTab == 'assigned_orders' ? 'active' : '',
                  'nav-link',
                ]"
                data-toggle="tab"
                href="#assigned_orders"
                role="tab"
                @click="changeTab('assigned_orders')"
                aria-controls="assigned_orders"
                aria-selected="false"
                ><i class="icofont-listine-dots"></i>
                <span class="d-none d-sm-inline">{{
                  $lang["My Assigned Orders"]
                }}</span></a
              >
            </li>
            <li v-if="user.user_type == 'delivery_boy'" class="nav-item">
              <a
                v-bind:class="[
                  activeTab == 'delivered_orders' ? 'active' : '',
                  'nav-link',
                ]"
                data-toggle="tab"
                href="#delivered_orders"
                role="tab"
                @click="changeTab('delivered_orders')"
                aria-controls="delivered_orders"
                aria-selected="false"
                ><i class="icofont-listine-dots"></i>
                <span class="d-none d-sm-inline">{{
                  $lang["Delivered Orders"]
                }}</span></a
              >
            </li>
            <li class="nav-item">
              <a
                v-bind:class="[
                  activeTab == 'update_profile' ? 'active' : '',
                  'nav-link',
                ]"
                data-toggle="tab"
                href="#update_profile"
                role="tab"
                @click="changeTab('update_profile')"
                aria-controls="update_profile"
                aria-selected="true"
                ><i class="icofont-user-alt-3"></i>
                <span class="d-none d-sm-inline">{{
                  $lang["Update Profile"]
                }}</span></a
              >
            </li>
            <li class="nav-item">
              <a
                v-bind:class="[
                  activeTab == 'update_profile_picture' ? 'active' : '',
                  'nav-link',
                ]"
                data-toggle="tab"
                href="#update_profile_picture"
                role="tab"
                @click="changeTab('update_profile_picture')"
                aria-controls="update_profile_picture"
                aria-selected="true"
                ><i class="icofont-image"></i>
                <span class="d-none d-sm-inline">{{
                  $lang["Update Profile Picture"]
                }}</span></a
              >
            </li>
            <li class="nav-item">
              <a href="#" @click="logout" class="nav-link"
                ><i class="icofont-exit"></i>
                <span class="d-none d-sm-inline">{{ $lang["Logout"] }}</span></a
              >
            </li>
          </ul>
        </div>
        <div class="col-lg-12">
          <div class="tab-content" id="accountTabContent">
            <div
              v-bind:class="[
                activeTab == 'profile' ? 'show active' : '',
                'tab-pane fade',
              ]"
              id="profile"
            >
              <div class="card">
                <div class="card-header">
                  <h4 class="header-title">{{ $lang["Profile Overview"] }}</h4>
                </div>
                <div class="card-body">
                  <table v-if="user.id" class="table table-bordered">
                    <tbody>
                      <tr>
                        <td>{{ $lang["Name"] }}</td>
                        <td>{{ user.name }}</td>
                      </tr>
                      <tr>
                        <td>{{ $lang["Email"] }}</td>
                        <td>{{ user.email }}</td>
                      </tr>
                      <tr>
                        <td>{{ $lang["Mobile"] }}</td>
                        <td>{{ user.phone }}</td>
                      </tr>
                      <tr>
                        <td>{{ $lang["Shipping Address"] }}</td>
                        <td>{{ user.address }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div
              v-if="user.user_type == 'customer'"
              v-bind:class="[
                activeTab == 'orders' ? 'show active' : '',
                'tab-pane fade',
              ]"
              id="orders"
            >
              <MyOrders />
            </div>

            <div
              v-if="
                user.user_type == 'delivery_boy' &&
                activeTab == 'assigned_orders'
              "
              v-bind:class="[
                activeTab == 'assigned_orders' ? 'show active' : '',
                'tab-pane fade',
              ]"
              id="assigned_orders"
            >
              <MyAssignedOrders />
            </div>

            <div
              v-if="
                user.user_type == 'delivery_boy' &&
                activeTab == 'delivered_orders'
              "
              v-bind:class="[
                activeTab == 'delivered_orders' ? 'show active' : '',
                'tab-pane fade',
              ]"
              id="delivered_orders"
            >
              <MyDeliveredOrders />
            </div>

            <div
              v-bind:class="[
                activeTab == 'update_profile' ? 'show active' : '',
                'tab-pane fade',
              ]"
              id="update_profile"
            >
              <div class="card">
                <div class="card-header">
                  <h4 class="header-title">{{ $lang["Update Profile"] }}</h4>
                </div>
                <div class="card-body">
                  <form class="form-signup" @submit.prevent="updateMyProfile">
                    <div class="form-label-group mb-3">
                      <label for="name">{{ $lang["Name"] }} *</label>
                      <input
                        type="text"
                        id="name"
                        v-bind:class="
                          errors && errors.name
                            ? 'form-control is-invalid'
                            : 'form-control'
                        "
                        v-model="user.name"
                        :placeholder="$lang['Name']"
                      />
                      <span class="text-danger" v-if="errors && errors.name">
                        {{ errors.name[0] }}
                      </span>
                    </div>

                    <div class="form-label-group mb-3">
                      <label for="email">{{ $lang["Email address"] }} *</label>
                      <input
                        type="email"
                        id="email"
                        v-bind:class="
                          errors && errors.email
                            ? 'form-control is-invalid'
                            : 'form-control'
                        "
                        v-model="user.email"
                        :placeholder="$lang['Email address']"
                      />
                      <span class="text-danger" v-if="errors && errors.email">
                        {{ errors.email[0] }}
                      </span>
                    </div>

                    <div class="form-label-group mb-3">
                      <label for="phone">{{ $lang["Mobile Number"] }} *</label>
                      <input
                        type="text"
                        id="phone"
                        v-bind:class="
                          errors && errors.phone
                            ? 'form-control is-invalid'
                            : 'form-control'
                        "
                        v-model="user.phone"
                        :placeholder="$lang['Mobile Number']"
                      />
                      <span class="text-danger" v-if="errors && errors.phone">
                        {{ errors.phone[0] }}
                      </span>
                    </div>

                    <div class="form-label-group mb-3">
                      <label for="phone"
                        >{{ $lang["Shipping Address"] }} *</label
                      >
                      <textarea
                        id="address"
                        v-bind:class="
                          errors && errors.address
                            ? 'form-control is-invalid'
                            : 'form-control'
                        "
                        v-model="user.address"
                        :placeholder="$lang['Shipping Address']"
                      ></textarea>
                      <span class="text-danger" v-if="errors && errors.address">
                        {{ errors.address[0] }}
                      </span>
                    </div>

                    <div class="form-label-group mb-3">
                      <label for="password">{{ $lang["Password"] }}</label>
                      <input
                        type="password"
                        id="password"
                        v-bind:class="
                          errors && errors.password
                            ? 'form-control is-invalid'
                            : 'form-control'
                        "
                        v-model="user.password"
                        :placeholder="$lang['Password']"
                      />
                      <span
                        class="text-danger"
                        v-if="errors && errors.password"
                      >
                        {{ errors.password[0] }}
                      </span>
                    </div>

                    <div class="form-label-group mb-3">
                      <label for="password">{{
                        $lang["Confirm Password"]
                      }}</label>
                      <input
                        type="password"
                        id="password_confirmation"
                        class="form-control"
                        v-model="user.password_confirmation"
                        :placeholder="$lang['Confirm Password']"
                      />
                    </div>

                    <button
                      type="submit"
                      class="btn btn-lg btn-primary text-uppercase"
                    >
                      <span
                        v-if="loading"
                        class="spinner-border spinner-border-sm"
                      ></span>
                      <span v-if="!loading">{{ $lang["Update Profile"] }}</span>
                    </button>
                  </form>
                </div>
              </div>
            </div>
            <div
              v-bind:class="[
                activeTab == 'update_profile_picture' ? 'show active' : '',
                'tab-pane fade',
              ]"
              id="update_profile_picture"
            >
              <div class="card">
                <div class="card-header d-flex align-items-center">
                  <h4 class="header-title">
                    {{ $lang["Update Profile Picture"] }}
                  </h4>
                  <button
                    type="button"
                    @click="selectFileInput"
                    class="btn btn-light text-dark ml-auto"
                  >
                    <i class="icofont-image"></i> {{ $lang["Select Image"] }}
                  </button>
                </div>
                <div class="card-body text-center">
                  <img
                    :src="user.profile_picture"
                    class="img-thumbnail profile-picture"
                  />
                  <span
                    class="text-danger"
                    v-if="errors && errors.profile_picture"
                  >
                    {{ errors.profile_picture[0] }}
                  </span>

                  <input
                    type="file"
                    class="d-none"
                    ref="inputFile"
                    v-on:change="onSelectImage"
                  />
                  <br />

                  <button
                    type="button"
                    @click="updateMyProfilePicture"
                    class="btn btn-primary mt-3"
                  >
                    <span
                      v-if="loading"
                      class="spinner-border spinner-border-sm"
                    ></span>
                    <span v-if="!loading">
                      <i class="icofont-cloud-upload"></i>
                      {{ $lang["Update Profile Picture"] }}</span
                    >
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import MyOrders from "../components/Orders.vue";
import MyAssignedOrders from "../components/AssignedOrders.vue";
import MyDeliveredOrders from "../components/DeliveredOrders.vue";

export default {
  components: {
    MyOrders,
    MyAssignedOrders,
    MyDeliveredOrders,
  },
  data() {
    return {
      profilePicture: null,
    };
  },
  computed: {
    ...mapGetters("auth", ["user", "loading", "errors"]),
    activeTab() {
      if (this.$route.params.activeTab) {
        return this.$route.params.activeTab;
      }
      return "profile";
    },
  },
  methods: {
    ...mapActions("auth", ["updateProfile", "updateProfilePicture"]),
    changeTab(tab) {
      if (this.$route.params.activeTab != tab) {
        this.$router.replace({ name: "profile", params: { activeTab: tab } });
      }
    },
    selectFileInput(event) {
      this.$refs.inputFile.click();
    },
    onSelectImage(e) {
      this.profilePicture = e.target.files[0];
      this.user.profile_picture = URL.createObjectURL(this.profilePicture);
    },
    updateMyProfile() {
      this.updateProfile().then(() => {
        this.$awn.success(this.$lang["Updated sucessfully"]);
      });
    },
    updateMyProfilePicture() {
      if (this.profilePicture != null) {
        let data = new FormData();
        data.append("profile_picture", this.profilePicture);
        this.updateProfilePicture(data).then(() => {
          this.$awn.success(this.$lang["Updated sucessfully"]);
        });
      }
    },
    logout: function () {
      this.$store.dispatch("auth/logout").then(() => {
        this.$router.push("/login");
      });
    },
  },
};
</script>
