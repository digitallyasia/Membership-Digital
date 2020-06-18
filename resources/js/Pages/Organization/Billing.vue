<template>
  <div class="-m-12">
    <main>
      <div class="flex flex-row p-12">
        <div class="flex flex-col flex-wrap content-start w-1/2 xl:w-2/3 md:flex-row">
          <div class="w-1/2 mb-12">
            <div class="justify-center p-5 mr-4 text-center card sm:mr-4 lg:mr-12">
              <div class="flex flex-col text-center">
                <div class="mb-2 text-base font-bold">Your Current Organisation Plan</div>
                <h1
                  class="font-serif text-3xl font-semibold leading-none text-gray-900 sm:text-4xl lg:text-5xl"
                >{{$page.auth.organization.subscription.name}}</h1>
              </div>
            </div>
          </div>
          <!-- <div class="w-1/2 mb-12">
            <div class="mr-4 card sm:mr-4 lg:mr-12">
              
              <div class="flex flex-col pt-2 pl-12">
                <div class="mb-2 text-xl font-bold">You have a bill due</div>
              </div>
            </div>
          </div>-->
          <div class="w-full mb-12" v-if="pendingPayment">
            <div class="items-center justify-start mr-4 card sm:mr-4 lg:mr-12">
              <div class="flex flex-col">
                <div class="mb-2 font-serif text-xl font-bold text-gray-900">You have a bill due</div>
                <div>
                  Plan:
                  <span
                    class="font-serif font-semibold"
                  >{{ plans.find(({ id }) => id === pendingPayment.plan_id).name}}</span>
                </div>
                <div>
                  Price:
                  <span class="font-serif font-semibold">{{ pendingPayment.amount}}</span>
                </div>
              </div>
              <a
                class="flex items-center justify-center ml-auto font-bold text-center rounded-lg btn-indigo"
                :href="`https://www.billplz-sandbox.com/bills/${pendingPayment.bill_id}`"
                target="_blank"
              >Pay Now</a>
              <button
                :disabled="canceling"
                class="flex items-center justify-center ml-2 font-bold text-center rounded-lg btn-indigo"
                @click="cancelUpgrade"
              >
                <div v-if="canceling" class="mr-2 btn-spinner" />Cancel Upgrade
              </button>
            </div>
          </div>
          <div
            class="items-center w-full"
            v-if="!showPackages && !pendingPayment && $page.auth.organization.subscription.id ===1"
          >
            <div class="items-center mr-4 card sm:mr-4 lg:mr-12">
              <div class="flex flex-col">
                <div class="text-xl font-bold">Would you like to upgrade your package?</div>
              </div>
              <button
                class="font-bold text-center rounded-lg btn-indigo"
                @click="showPackages = true"
              >Upgrade Package</button>
            </div>
          </div>
        </div>
        <div class="w-1/2">
          <div class="w-full overflow-x-auto bg-white rounded shadow-md">
            <table class="w-full whitespace-no-wrap">
              <tr class="font-bold text-left bg-gray-300">
                <th class="px-6 py-3">Plan</th>
                <th class="px-6 py-3">Subscription</th>
                <th class="px-6 py-3">Amount</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Paid At</th>
              </tr>
              <tr
                v-for="payment in payments.data"
                :key="payment.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100"
              >
                <td class="border-t">
                  <span class="flex items-center px-6 py-4" tabindex="-1">{{ payment.plan.name }}</span>
                </td>
                <td class="border-t">
                  <span
                    class="flex items-center px-6 py-4 capitalize"
                    tabindex="-1"
                  >{{ payment.subscription }}</span>
                </td>
                <td class="border-t">
                  <span class="flex items-center px-6 py-4" tabindex="-1">{{ payment.amount }}</span>
                </td>
                <td class="border-t">
                  <span class="flex items-center px-4" tabindex="-1">
                    <span
                      v-if="payment.state === 'paid'"
                      class="px-4 py-2 font-bold text-green-500 bg-green-200 rounded-full"
                    >Paid</span>
                    <span
                      v-if="payment.state === 'due'"
                      class="px-4 py-2 font-bold text-red-500 bg-red-200 rounded-full"
                    >Due</span>
                  </span>
                </td>
                <td class="border-t">
                  <span class="flex items-center px-6 py-4" tabindex="-1">{{ payment.paid_at }}</span>
                </td>
              </tr>
              <tr v-if="payments.data.length === 0">
                <td class="px-6 py-4 text-center border-t" colspan="5">No history of payments</td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="pb-12 pl-12 sm:mr-4 lg:mr-12" v-if="showPackages">
        <div class="px-4 pt-12 bg-gray-900 sm:px-6 lg:px-8 rounded-t-md">
          <div class="text-center">
            <p
              class="max-w-xl mx-auto mt-2 text-xl leading-7 text-gray-300 lg:max-w-4xl lg:text-2xl lg:leading-8"
            >Automating your organisation is cheaper than hiring an admin executive.</p>
            <div class="mt-8">
              <button
                class="c-toggle"
                :class="{'c-toggle--active':isYearly}"
                @click="changeSubscription"
              >
                <div class="c-toggle__handle"></div>
                <span class="c-toggle__label">{{isYearly ? 'Yearly':'Monthly'}}</span>
              </button>
            </div>
          </div>
        </div>
        <div class="flex justify-center p-12 pt-8 bg-gray-900">
          <div class="plans">
            <div
              :class="'mycard flex  flex-col '+(plan.code)"
              v-for="plan in plans"
              :key="plan.name"
            >
              <div class="font-serif font-semibold title">
                <h2>{{plan.name}}</h2>
              </div>
              <div class="flex items-baseline justify-center price">
                <h4>
                  <span class="text-3xl">RM</span>
                  {{isYearly? plan.yearly_price:plan.monthly_price}}
                </h4>
                <span class="text-white">{{isYearly?'Yearly':'Monthly'}}</span>
              </div>
              <div class="option">
                <ul>
                  <!-- <li v-for="detail in plan.details" :key="detail">{{detail}}</li> -->
                  <li v-if="plan.card_customization">Card Customization</li>
                  <li v-if="plan.membership_number">Membership Number</li>
                  <li v-if="plan.import_membership_csv">Import Membership CSV</li>
                  <li>Up to {{plan.number_of_benefits}} Benefits</li>
                  <li>Up to {{plan.number_of_announcements}} Announcement</li>
                  <li>Up to {{plan.number_of_notifications}} Push Notifications</li>
                  <li>Up to {{plan.number_of_members}} Total Members</li>
                </ul>
              </div>
              <div class="mt-auto">
                <div class="mt-2 rounded-lg shadow-md">
                  <button
                    class="block w-full px-6 py-4 text-base font-semibold leading-6 text-center text-teal-600 transition duration-150 ease-in-out bg-white rounded-lg cursor-pointer font-display hover:text-teal-500 focus:outline-none focus:shadow-outline"
                    @click="changePlan(plan)"
                    v-text="plan.id === $page.auth.organization.plan_id ? 'Current Plan': selectedPlan && selectedPlan.id === plan.id?'Selected Plan': 'Change Plan'"
                  ></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center bg-gray-900 rounded-b-md" v-if="selectedPlan">
          <h2
            class="font-serif text-3xl font-semibold leading-none text-white sm:text-4xl lg:text-5xl"
            v-text="selectedPlan.name"
          ></h2>
          <div class="flex items-baseline justify-center mt-4">
            <span
              class="pr-1 font-serif text-2xl font-semibold leading-none text-white sm:text-5xl lg:text-3xl"
            >RM</span>
            <h1
              class="font-serif text-4xl font-semibold leading-none text-white sm:text-5xl lg:text-6xl"
            >{{isYearly? selectedPlan.yearly_price:selectedPlan.monthly_price}}</h1>
            <span
              class="pl-1 font-serif text-2xl font-semibold leading-none text-white sm:text-5xl lg:text-3xl"
            >
              <span class="text-white">{{isYearly?'Yearly':'Monthly'}}</span>
            </span>
          </div>
          <button
            :disabled="generatingBill"
            class="inline-flex items-center justify-center w-56 px-6 py-4 mt-3 mb-10 text-2xl font-bold text-center text-black text-gray-900 bg-white rounded-lg rounded-full btn-indigo focus:shadow-outline"
            @click="generateBill"
          >
            <div v-if="generatingBill" class="mr-2 btn-spinner" />Upgrade
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import mapValues from "lodash/mapValues";
import Pagination from "@/Shared/Pagination";
import pickBy from "lodash/pickBy";
import debounce from "lodash/debounce";
import diff from "../../../../vendor/scrivo/highlight.php/Highlight/languages/diff.json";
import PackageListItem from "@/shared/PackageListItem";
import LoadingButton from "@/Shared/LoadingButton";
export default {
  metaInfo: { title: "Members" },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    PackageListItem,
    LoadingButton
  },
  props: {
    members: Object,
    payments: Object,
    plans: Array,
    pendingPayment: Object
  },
  data() {
    return {
      isYearly: false,
      activeTab: "Accepted",
      showPackages: false,
      selectedPlan: null,
      selectedSubscription: "monthly",
      canceling: false,
      generatingBill: false
    };
  },
  methods: {
    changeSubscription() {
      this.isYearly = !this.isYearly;
      this.selectedSubscription = this.isYearly ? "yearly" : "monthly";
    },
    changePlan(plan) {
      console.log(plan);
      if (plan.id !== 1) {
        this.selectedPlan = plan;
      } else {
        this.selectedPlan = null;
      }
    },
    cancelUpgrade() {
      this.canceling = true;
      this.$inertia
        .post(this.route("organization.bill.delete"), {
          bill_id: this.pendingPayment.bill_id
        })
        .then(respose => {
          this.canceling = false;
        });
    },
    generateBill() {
      this.generatingBill = true;
      this.$inertia
        .post(this.route("organization.bill.generate"), {
          plan_id: this.selectedPlan.id,
          subscription: this.selectedSubscription
        })
        .then(respose => {
          this.generatingBill = false;
          this.showPackages = false;
          this.selectedPlan = null;
        });
    }
  }
};
</script>
<style scoped>
.plans {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  grid-gap: 50px;
  align-items: stretch;
  width: 100%;
  margin: auto 0;
}

@media (min-width: 576px) and (max-width: 767.98px) {
  .plans {
    width: 540px;
  }
}

@media (min-width: 768px) and (max-width: 991.98px) {
  .plans {
    width: 720px;
  }
}

@media (min-width: 992px) {
  .plans {
    width: 960px;
  }
}

@media (min-width: 1200px) {
  .plans {
    width: 1140px;
  }
}

.mycard {
  text-align: center;
  position: relative;
  border-radius: 15px;
  padding: 40px 20px 20px;
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
  transition: 0.5s;
  overflow: hidden;
}

.mycard:hover {
  transform: scale(1.1);
}

.free,
.free .title .fa {
  background: linear-gradient(-45deg, #f403d1, #64b5f6);
}

.standard,
.standard .title .fa {
  background: linear-gradient(-45deg, #ffec61, #f321d7);
}

.premium,
.premium .title .fa {
  background: linear-gradient(-45deg, #24ff72, #9a4eff);
}

.mycard::before {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 40%;
  background: rgba(255, 255, 255, 0.1);
  z-index: 1;
  transform: skewY(-5deg) scale(1.5);
}

.title .fa {
  color: #fff;
  font-size: 60px;
  width: 100px;
  height: 100px;
  border-radius: 50%;
  text-align: center;
  line-height: 100px;
  box-shadow: 0 10px 10px rgba(0, 0, 0, 0.2);
}

.title h2 {
  position: relative;
  color: #fff;
  font-size: 32px;
  z-index: 2;
}

.price {
  position: relative;
  z-index: 2;
}

.price h4 {
  margin: 0;
  padding: 10px 0;
  color: #fff;
  font-size: 50px;
}

.option {
  position: relative;
  z-index: 2;
}

.option ul {
  margin: 0;
  padding: 0;
}

.option ul li {
  margin: 0 0 10px;
  padding: 0;
  list-style: none;
  color: #fff;
  font-size: 16px;
}

.mycard button {
  position: relative;
  z-index: 2;
  border-radius: 8px;
  text-decoration: none;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
  font-weight: bold;
}
@import url("https://fonts.googleapis.com/css?family=Roboto:300");

body {
  background: #262626;
  box-sizing: border-box;
  font-family: "Roboto", sans-serif !important;
  height: 100vh;
  display: flex;
  justify-content: center;
}

:root {
  --neutral-dark: #585560;
  --neutral-darker: #22202c;
  --neutral-full: #000000;
  --root-bg: linear-gradient(60deg, #f4f4f4, #cccace);
  --root-color: #12101d;
  --surface: linear-gradient(60deg, #f4f4f4, #fff, #fff);
}
button {
  cursor: pointer;
  font-family: inherit;
}
button:focus,
button:active {
  outline: 0;
}

.l-wrapper {
  width: 100%;
  padding-left: 1.6rem;
  padding-right: 1.6rem;
  padding-top: 3.2rem;
}
@media screen and (min-width: 500px) {
  .l-wrapper {
    max-width: 350px;
    padding-top: 20vh;
  }
}

.c-toggle {
  display: -webkit-inline-box;
  display: inline-flex;
  width: 10.2rem;
  border: 0;
  border-radius: 999px;
  padding: 0;
  margin: 0;
  background: -webkit-gradient(
    linear,
    left bottom,
    left top,
    from(#ff3f34),
    to(#ff5e57)
  );
  background: linear-gradient(to top, #ff3f34, #ff5e57);
  height: 3.2rem;
  box-shadow: inset 0px 0px 0.4rem rgba(0, 0, 0, 0.15);
  position: relative;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  border: 0.1rem solid #e3e1e6;
  -webkit-transition: all 200ms ease-out 0s;
  transition: all 200ms ease-out 0s;
}
.c-toggle:after {
  content: "";
  position: absolute;
  top: 0rem;
  left: 0rem;
  right: 0rem;
  bottom: 0rem;
  border-radius: 999px;
  box-shadow: 0px 0px 0.4rem 0.2rem #e3e1e6;
  -webkit-transition: all 200ms ease-out 0s;
  transition: all 200ms ease-out 0s;
}
.c-toggle:active {
  box-shadow: 0px 0px 0px 0.4rem rgba(30, 144, 255, 0.38);
}
.c-toggle:hover:after,
.c-toggle:focus:after {
  background: -webkit-gradient(
    linear,
    left bottom,
    left top,
    from(rgba(255, 255, 255, 0)),
    to(rgba(255, 255, 255, 0.3))
  );
  background: linear-gradient(
    to top,
    rgba(255, 255, 255, 0),
    rgba(255, 255, 255, 0.3)
  );
}
.c-toggle__handle {
  position: absolute;
  left: 0.4rem;
  top: 50%;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
  border-radius: 50%;
  box-shadow: 0px 2px 4px -1px rgba(0, 0, 0, 0.15),
    0px 5px 10px -3px rgba(0, 0, 0, 0.1);
  background: -webkit-gradient(
    linear,
    left bottom,
    left top,
    from(#f4f4f4),
    to(#cccace)
  );
  background: linear-gradient(to top, #f4f4f4, #cccace);
  height: 2.4rem;
  width: 2.4rem;
  -webkit-transition: all 200ms ease-out 0s;
  transition: all 200ms ease-out 0s;
}
.c-toggle__handle:before {
  content: "";
  position: absolute;
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
  border-radius: 50%;
  box-shadow: inset 0px 0.1rem 0.2rem white;
}
.c-toggle__label {
  font-size: 1.1rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: white;
  position: absolute;
  right: 0.8rem;
  top: 50%;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
  font-weight: 700;
  text-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3);
  -webkit-transition: right 300ms ease-out 100ms;
  transition: right 300ms ease-out 100ms;
}
.c-toggle--active {
  background: -webkit-gradient(
    linear,
    left bottom,
    left top,
    from(#27ae60),
    to(#2ecc71)
  );
  background: linear-gradient(to top, #27ae60, #2ecc71);
}
.c-toggle--active .c-toggle__handle {
  left: 7.3rem;
}
.c-toggle--active .c-toggle__label {
  right: 3.5rem;
}

.u-text--secondary {
  color: #a4a3a7 !important;
}
</style>