<?php get_header(); 
?>

<section class="wrapper">
  <div class="blue">
  <h1>Is udvalg</h1>
 <p class="widht">Herunder kan du se vores is udvalg på nuværende tidspunkt. Udvalget er bredt og skiftest lobende, oftest fra sæson til sæson.
  Vi har både nogle klassiske smage, såsom vanilje med mørk chokolade, lakrids og pistacie, til nogle mere eksperimenterende som fx. kokos sorbet med 
  hakket dild, havtorn og i edet tidligere forår, ramsløg med hasselnød.
  <br>
  <br>
  Hver eneste is er lavet med kærlighed, hvilket kommer til udtryk <br>  både i smagen og æstetikken, hvor hver detalje er nøje overvejet.
 </p>
  <div class="pink"><h2>Priser</h2> <br> <p> Ispinde fra 19,- til 39,- <br> Bæger fra 39,95,- til 45,- </p></div>
</div>
</section>

<template id="produkt-template">
  <article class="produkt-is-container">
    <img class="produkt-billede" src="" alt="">
    <h2 class="navn"></h2>
    <p class="pris"></p>
    <details class="ingredients"><summary>Ingredienser</summary><p class="ingredienser"></p></details>
  </article>
</template>

<div class="knap-container"></div>
<div class="produkt-container"></div>
<?php get_footer(); ?>

<script>

  // En event listener, som der checker at hele siden er loaded og først derefter starter den første funktion getJson();
  document.addEventListener("DOMContentLoaded", getJson);

  //Variable for hhv. vores produkter samt for vores filtrering
  let produkter;
  let filter = "alle";

      const popupModal = document.querySelector("#popup");
    const overlay = document.querySelector(".overlayish")

  //dbUrl = Database med prodtuker 
  const dbUrl = "https://helbo.one/kea/4_semester_final/wp-json/wp/v2/produkt?per_page=100";

  //catUrl = Alle vores kategoriger for vores produkter
  const catUrl = "https://helbo.one/kea/4_semester_final/wp-json/wp/v2/categories";

  // const specificUrl = "https://helbo.one/kea/4_semester_final/wp-json/wp/v2/produkt/"+<?php echo get_the_ID() ?>;
  // Funktion som indhenter JSON data fra wordpress rest api
  // Asynkron funktion = Det bliver ikke hentet samtidigt
  // Data der bliver indhentet er alle produkterne + kategoriger
  async function getJson() {
    const data = await fetch(dbUrl);
    const catData = await fetch(catUrl);
    produkter = await data.json();
    knapCat = await catData.json()

   // getJsonTwo();
    addButton();
    visIs();
  }

  //Denne funktion tilføjer en knap, for hver eksiterende kategori vi har i vores PODS
  function addButton() {

    //Først finder vi containeren, hvori knapperne (kategorigerne) skal være i
    let knapContainer = document.querySelector(".knap-container");

    //ForEach loop, som tiljøer en knap for hver kategori, med data-kategori attributten, lig med kategori ID + knappens indhold lig med kategori navn
    knapCat.forEach((kategori) => {

      //Hvis kategori.id = 1 (hvilket er ID'et for alle kategorier) tilføjes klassen "valgt"
      if (kategori.id === 1) {
        knapContainer.innerHTML += `<button class="valgt filter" data-kategori="${kategori.id}">${kategori.name}</button>`
      } else {
        //Hvis ikke så tilføjes knappen blot
        knapContainer.innerHTML += `<button class="filter" data-kategori="${kategori.id}">${kategori.name}</button>`;
      }
    })

    //Her tilføjer vi "click" på hver knap, så de er clickable.
    const btnEvent = () => {
      //I stedet for at tilføje "click" på hver knap, gør vi brug af document.qsAll, som laver et array af alle som matcher
      //Dette gøres også, så vi ikke gør brug af gentagen kode om og om
      document.querySelectorAll(".knap-container button").forEach(btn => {
        btn.addEventListener("click", filtrerProdukter)
      })
    }
    btnEvent()
    return
  }

  //filtrerProdukter() som ændrer det valgte filter afhængig af hvilken knap som er valgt
  function filtrerProdukter() {
			filter = this.dataset.kategori;
      //Når en anden knap bliver valgt, som ikke er "Alle". Bliver klassen "valgt" fjernet fra alle og tilføjet til den knap man klikker efterfølgende
			document.querySelector(".valgt").classList.remove("valgt");
			this.classList.add("valgt");
			visIs();
	}
	let produkt;

  function visIs() {
    let temp = document.getElementById("produkt-template");
    let container = document.querySelector(".produkt-container");
    container.innerHTML = "";
    console.log(produkter)
    produkter.forEach(produkt => {
      if (produkt.categories.includes(parseInt(filter)) || filter == "alle") {
      let klon = temp.content.cloneNode(true);
      klon.querySelector("h2").textContent = produkt.navn;
      klon.querySelector("img").src = produkt.billede.guid;
      klon.querySelector(".pris").textContent = produkt.pris;
      klon.querySelector(".ingredienser").innerHTML = produkt.ingredienser;
      container.appendChild(klon);
        }
    });
  }
</script>
