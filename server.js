import express from "express";
import dotenv from "dotenv";
import stripe from "stripe";

//load variable
dotenv.config();

//star server
const app = express();

app.use(express.static("public"));
app.use(express.json());

//Home Route
app.get("/", (req, res) => {
    res.sendFile("Main.html", { root: "public"});
});

//stripe
let stripeGateway = stripe(procces.env.stripe_api);

app.listen(55886, () => {
    console.log("listening on port 55886;");
});