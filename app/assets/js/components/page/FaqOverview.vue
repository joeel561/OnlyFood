<template>
<div class="overview faq-overview col-12">
    <h1>Support</h1>
    <div class="panel col-12 support-panel">
        <p>
            Here you can find answers to the most frequently asked questions. If you can't find the answer to your question, please contact us.
        </p>
    </div>
    <div class="panel faq-panel col-12 accordion" id="faqOverview">
        <div class="col-12 col-md-6">
            <div class="faq-box accordion-item">
                <div class="faq-box--title d-flex justify-content-between accordion-header collapsed" id="faqHeadingOne"  data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
                    <h5>How to create a recipe?</h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z"/>
                        <path class="plus-minus-path" fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 7v10"/>
                        <path fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 12h10"/>
                    </svg>
                </div>
                <div class="faq-box--content accordion-collapse collapse" id="faqCollapseOne" aria-labelledby="faqHeadingOne" data-bs-parent="#faqOverview">
                    <p> 
                        First you go on the recipes page and click on the "Create Recipe" button. Then you fill out the form and click on the "Create" button. You can also edit a recipe by clicking on the "Edit" button on the recipe detail page.
                    </p>
                </div>
            </div>
            <div class="faq-box accordion-item">
                <div class="faq-box--title d-flex justify-content-between accordion-header collapsed" id="faqHeadingTwo"  data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="true" aria-controls="faqCollapseTwo">
                    <h5>Delete my Account</h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z"/>
                        <path class="plus-minus-path" fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 7v10"/>
                        <path fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 12h10"/>
                    </svg>
                </div>
                <div class="faq-box--content accordion-collapse collapse" id="faqCollapseTwo" aria-labelledby="faqHeadingTwo" data-bs-parent="#faqOverview">
                    <p>
                        You can delete your account & all of your data by clicking on the "Delete Account" button on the account page. All data associated with your account will also be deleted.
                    </p>
                </div>
            </div>
            <div class="faq-box accordion-item">
                <div class="faq-box--title d-flex justify-content-between accordion-header collapsed" id="faqHeadingThree"  data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="true" aria-controls="faqCollapseThree">
                    <h5>Edit a recipe</h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z"/>
                        <path class="plus-minus-path" fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 7v10"/>
                        <path fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 12h10"/>
                    </svg>
                </div>
                <div class="faq-box--content accordion-collapse collapse" id="faqCollapseThree" aria-labelledby="faqHeadingThree" data-bs-parent="#faqOverview">
                    <p>
                        You can edit a recipe by clicking on the "Edit" button on the recipe detail page.
                    </p>
                </div>
            </div>
            <div class="faq-box accordion-item">
                <div class="faq-box--title d-flex justify-content-between accordion-header collapsed" id="faqHeadingFour"  data-bs-toggle="collapse" data-bs-target="#faqCollapseFour" aria-expanded="true" aria-controls="faqCollapseFour">
                    <h5>Search for a recipe</h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z"/>
                        <path class="plus-minus-path" fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 7v10"/>
                        <path fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 12h10"/>
                    </svg>
                </div>
                <div class="faq-box--content accordion-collapse collapse" id="faqCollapseFour" aria-labelledby="faqHeadingFour" data-bs-parent="#faqOverview">
                    <p>
                        You can search for a recipe by clicking on the "Search" button on the recipes page. You can search for a recipe by name or by the author for example if you search for "Joeel56"  & you will see all of my recipes.
                    </p>
                </div>
            </div>
        </div>  
        <div class="faq-bg-image-panel d-none d-md-block">
            <img src="../../../img/bg-faq-overview.svg" />
        </div>      
    </div>
</div>
</template>
<script>
 export default {
   data() {
     return {
     };
   },
    methods: {
    }
   }
</script>