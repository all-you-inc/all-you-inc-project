<?php

use shop\helpers\PriceHelper;
use yii\helpers\Html;

$this->title = $response['request'] . 'Terms & Condition';
$this->params['breadcrumbs'][] = $this->title;
?>


<main>

    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid ">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
            </ol>
        </div><!-- End .container-fluid  -->
    </nav>

    <div class="about-section">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-9 col-lg-offset-1 col-md-9 col-md-offset-1">
                    <h2 class="subtitle"><?= $this->title ?></h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="Translation">
                                <p>All You Inc. Terms and Conditions</p>
                                <p>All You Inc. policies are to ensure our talents, promoters, fans, customers, everyone is safe within our technology epicenter environment. We thrive to provide a diverse, genuine, fair, equal and unified standard for all.</p>
                                <p>Our standards apply to everyone, everywhere and to every type of content.</p>
                                <p>Please report any and all potentially violating content.</p>
                                <p>We are serious about holding all in our network to the highest standards, morals and values and there can be severe consequences for any who violate our policies.</p>
                                <p>Please follow:</p>
                                <p align="center">Violence and Incitement are not allowed</p>
                                <p>We aim to prevent potential offline harm that may be related to content on All You. While we understand that people commonly express disdain or disagreement by threatening or calling for violence in non-serious ways, we remove language that incites or facilitates serious violence. We remove content, disable accounts, and work with law enforcement when we believe there is a genuine risk of physical harm or direct threats to public safety. We also try to consider the language and context in order to distinguish casual statements from content that constitutes a credible threat to public or personal safety. In determining whether a threat is credible, we may also consider additional information like a person's public visibility and vulnerability.</p>
                                <p>In some cases, we see aspirational or conditional threats directed at terrorists and other violent actors (e.g. Terrorists deserve to be killed), and we deem those non credible absent specific evidence to the contrary.</p>
                                <p>&nbsp;</p>
                                <p>You are not allowed to post the following</p>
                                <p>Threats that could lead to death (and other forms of high-severity violence) of any target(s) where threat is defined as any of the following:</p>
                                <p>Statements of intent to commit high-severity violence; or</p>
                                <p>Calls for high-severity violence including content where no target is specified but a symbol represents the target and/or includes a visual of an armament to represent violence; or</p>
                                <p>Statements advocating for high-severity violence; or</p>
                                <p>Aspirational or conditional statements to commit high-severity violence</p>
                                <p>Content that asks or offers services for hire to kill others (for example, hitmen, mercenaries, assassins) or advocates for the use of a hitman, mercenary or assassin against a target.</p>
                                <p>Admissions, statements of intent or advocacy, calls to action, or aspirational or conditional statements to kidnap a target.</p>
                                <p>Threats that lead to serious injury (mid-severity violence) towards private individuals, minor public figures, vulnerable persons, or vulnerable groups where threat is defined as any of the following:</p>
                                <p>Statements of intent to commit violence; or</p>
                                <p>Statements advocating violence; or</p>
                                <p>Calls for mid-severity violence including content where no target is specified but a symbol represents the target; or</p>
                                <p>Aspirational or conditional statements to commit violence; or</p>
                                <p>Content about other target(s) apart from private individuals, minor public figures, vulnerable persons, or vulnerable groups and any credible:</p>
                                <p>Statements of intent to commit violence; or</p>
                                <p>Calls for action of violence; or</p>
                                <p>Statements advocating for violence; or</p>
                                <p>Aspirational or conditional statements to commit violence</p>
                                <p>Threats that lead to physical harm (or other forms of lower-severity violence) towards private individuals (self-reporting required) or minor public figures where threat is defined as any of the following:</p>
                                <p>Private individuals (name and/or face match are required) or minor public figures that includes:</p>
                                <p>Statements of intent, calls for action, advocating, aspirational or conditional statements to commit low-severity violence</p>
                                <p>Imagery of private individuals or minor public figures that has been manipulated to include threats of violence either in text or pictorial (adding bulls eye, dart, gun to head, etc.)</p>
                                <p>Any content created for the express purpose of outing an individual as a member of a designated and recognizable at-risk group</p>
                                <p>Instructions on how to make or use weapons if there&rsquo;s evidence of a goal to seriously injure or kill people, through:</p>
                                <p>Language explicitly stating that goal, or</p>
                                <p>Photos or videos that show or simulate the end result (serious injury or death) as part of the instruction</p>
                                <p>Unless the aforementioned content is shared as part of recreational self defense, for military training purposes, commercial video games, or news coverage (posted by Page or with news logo)</p>
                                <p>Providing instructions on how to make or use explosives:</p>
                                <p>Unless there is clear context that the content is for a non-violent purpose (for example part of commercial video games, clear scientific/educational purpose, fireworks, or specifically for fishing)</p>
                                <p>Any content containing statements of intent, calls for action, or advocating for high or mid-severity violence due to voting, voter registration, or the outcome of an election.</p>
                                <p>Misinformation that contributes to imminent violence or physical harm.</p>
                                <p>Calls to action, statements of intent to bring armaments to locations, including but not limited to places of worship, or encouraging others to do the same.</p>
                                <p><strong>Dangerous Individuals and Organizations are not allowed or welcomed</strong></p>
                                <p>In an effort to prevent and disrupt real-world harm, we do not allow any organizations or individuals that proclaim a violent mission or are engaged in violence, from having a presence on All You. This includes organizations or individuals involved in the following:</p>
                                <p>Terrorist activity</p>
                                <p>Organized hate</p>
                                <p>Mass or serial murder</p>
                                <p>Human trafficking</p>
                                <p>Organized violence or criminal activity</p>
                                <p>We also remove content that expresses support or praise for groups, leaders, or individuals involved in these activities.</p>
                                <p>&nbsp;</p>
                                <p>We do not allow the following people (living or deceased) or groups to maintain a presence (for example, have an account, Page, Group) on our platform:</p>
                                <p>Terrorist organizations and terrorists</p>
                                <p>A terrorist organization is defined as:</p>
                                <p>Any non-governmental organization that engages in premeditated acts of violence against persons or property to intimidate a civilian population, government, or international organization in order to achieve a political, religious, or ideological aim</p>
                                <p>A member of a terrorist organization or any person who commits a terrorist act is considered a terrorist</p>
                                <p>A terrorist act is defined as a premeditated act of violence against persons or property carried out by a non-government actor to intimidate a civilian population, government, or international organization in order to achieve a political, religious, or ideological aim.</p>
                                <p>Hate organizations and their leaders and prominent members</p>
                                <p>A hate organization is defined as:</p>
                                <p>Any association of three or more people that is organized under a name, sign, or symbol and that has an ideology, statements, or physical actions that attack individuals based on characteristics, including race, religious affiliation, nationality, ethnicity, gender, sex, sexual orientation, serious disease or disability.</p>
                                <p>Mass and serial murderers</p>
                                <p>We consider a homicide to be a mass murder if it results in four or more deaths in one incident</p>
                                <p>We consider any individual who has committed two or more murders over multiple incidents or locations a serial murderer</p>
                                <p>We make these assessments based upon the information available to us and will generally apply this policy to a mass or serial murderer who meets any of the following criteria:</p>
                                <p>They were convicted of mass or serial murder.</p>
                                <p>They were killed by law enforcement during commission of the mass or serial murder or during subsequent flight.</p>
                                <p>They killed themselves at the scene or in the aftermath of the mass or serial murder.</p>
                                <p>They were identified by law enforcement with images from the crime.</p>
                                <p>Human trafficking groups and their leaders</p>
                                <p>Human trafficking groups are organizations responsible for any of the following:</p>
                                <p>Prostitution of others, forced/bonded labor, slavery, or the removal of organs</p>
                                <p>Recruiting, transporting, transferring, detaining, providing, harboring, or receiving a minor, or an adult against their will</p>
                                <p>Criminal organizations and their leaders and prominent members</p>
                                <p>A criminal organization is defined as:</p>
                                <p>Any association of three or more people that is united under a name, color(s), hand gesture(s) or recognized indicia, that has engaged in or threatens to engage in criminal activity, including (but not limited to)</p>
                                <p>Homicide</p>
                                <p>Drug trafficking</p>
                                <p>Arms trafficking</p>
                                <p>Identity theft</p>
                                <p>Money laundering</p>
                                <p>Extortion or trafficking</p>
                                <p>Assault</p>
                                <p>Kidnapping</p>
                                <p>Sexual exploitation &nbsp;</p>
                                <p>We do not allow symbols that represent any of the above organizations or individuals to be shared on our platform without context that condemns or neutrally discusses the content.</p>
                                <p>We do not allow content that praises any of the above organizations or individuals or any acts committed by them.</p>
                                <p>We do not allow coordination of support for any of the above organizations or individuals or any acts committed by them.</p>
                                <p><strong>Promoting or Publicizing Crime are not allowed</strong></p>
                                <p>We prohibit people from promoting or publicizing violent crime, theft, and/or fraud because we do not want to condone this activity and because there is a risk of copycat behavior. We also do not allow people to depict criminal activity or admit to crimes they or their associates have committed. We do, however, allow people to debate or advocate for the legality of criminal activities, as well as address them in a rhetorical or satirical way.</p>
                                <p>&nbsp;</p>
                                <p>You are not allowed to post</p>
                                <p>Content depicting, admitting, or promoting the following criminal acts committed by you or your associates</p>
                                <p>Acts of physical harm committed against people</p>
                                <p>Acts of physical harm committed against animals except in cases of hunting, fishing, religious sacrifice, or food preparation/processing</p>
                                <p>Poaching or selling endangered species or their parts</p>
                                <p>Staged animal vs. animal fights</p>
                                <p>Theft</p>
                                <p>Vandalism or property damage</p>
                                <p>Fraud</p>
                                <p>Trafficking</p>
                                <p>Sexual violence or sexual exploitation, including sexual assault.</p>
                                <p><strong>Coordinating Harm is not allowed</strong></p>
                                <p>In an effort to prevent and disrupt real-world harm, we prohibit people from facilitating or coordinating future activity, criminal or otherwise, that is intended or likely to cause harm to people, businesses, or animals. People can draw attention to harmful activity that they may witness or experience as long as they do not advocate for or coordinate harm.</p>
                                <p>&nbsp;</p>
                                <p>You are not allowed to post the following:</p>
                                <p>Statements of intent, calls to action, or advocating for the following:</p>
                                <p>Acts of physical harm committed against people</p>
                                <p>Acts of physical harm committed against animals except in cases of hunting, fishing, religious sacrifice, or food preparation/processing</p>
                                <p>Staged animal vs. animal fights</p>
                                <p>Theft</p>
                                <p>Vandalism/property damage</p>
                                <p>Swatting</p>
                                <p>Fraud, defined as the deliberate deception to take advantage of another, secure an unfair gain, or deprive another of money, property, or legal right. Examples of fraud include, but are not limited to:</p>
                                <p>Bribery</p>
                                <p>Embezzlement</p>
                                <p>Money Laundering (concealment of the origins of criminally obtained money)</p>
                                <p>Supporting and/or facilitating the misuse of payment cards</p>
                                <p>Voter fraud, defined as any offers to buy or sell votes with cash or gifts</p>
                                <p>Voter suppression, defined as:</p>
                                <p>Misrepresentation of the dates, locations, and times, and methods for voting or voter registration</p>
                                <p>Misrepresentation of who can vote, qualifications for voting, whether a vote will be counted, and what information and/or materials must be provided in order to vote.</p>
                                <p>Other misrepresentations related to voting in an official election may be subject to false news standards.</p>
                                <p>Arranged marriages with refugees or internally displaced persons</p>
                                <p>Trafficking.</p>
                                <p>Sexual violence or sexual exploitation, including sexual assault.</p>
                                <p>Offers of services to smuggle or assist in smuggling people.</p>
                                <p>Content that depicts, promotes, advocates for or encourages participation in a high risk viral challenge, including content with no caption or one that expresses a neutral sentiment.</p>
                                <p>To encourage safety and compliance with common legal restrictions, we prohibit attempts by individuals, manufacturers, and retailers to purchase, sell, or trade non-medical drugs, pharmaceutical drugs, and marijuana. We also prohibit the purchase, sale, gifting, exchange, and transfer of firearms, including firearm parts or ammunition, between private individuals on All You. Some of these items are not regulated everywhere; however, because of the borderless nature of our policies, we try to enforce our policies as consistently as possible. Firearm stores and online retailers may promote items available for sale off of our services as long as those retailers comply with all applicable laws and regulations. We allow discussions about sales of firearms and firearm parts in stores or by online retailers and advocating for changes to firearm regulation. Regulated goods that are not prohibited by our site and apps.</p>
                                <p>&nbsp;</p>
                                <p>You are not allowed to post:</p>
                                <p>Content about non-medical drugs that</p>
                                <p>Coordinates or encourages others to sell non-medical drugs</p>
                                <p>Depicts, admits to, attempts purchase, or promotes sales of non-medical drugs by the poster of the content or their associates</p>
                                <p>Promotes, encourages, coordinates, or provides instructions for use or make of non-medical drugs</p>
                                <p>Admits, either in writing or verbally, to personal use of non-medical drugs unless posted in a recovery context</p>
                                <p>Content that depicts the sale or attempt to purchase marijuana and pharmaceutical drugs. This includes content that</p>
                                <p>Mentions or depicts marijuana or pharmaceutical drugs</p>
                                <p>Makes an attempt to sell or trade, by which we mean any of the following:</p>
                                <p>Explicitly mentioning the product is for sale or trade or delivery</p>
                                <p>Asking the audience to buy</p>
                                <p>Listing the price</p>
                                <p>Encouraging contact about the product either by explicitly asking to be contacted or including any type of contact information</p>
                                <p>Attempting to solicit the product, defined as:</p>
                                <p>Stating interest in buying the product, or</p>
                                <p>Asking if anyone has the product for sale/trade</p>
                                <p>This applies to both individual pieces of content and Pages and Groups primarily dedicated to the sale of marijuana or pharmaceutical drugs</p>
                                <p>Content that attempts to offer, sell, gift, exchange, or transfer firearms, firearm parts, ammunition, or explosives between private individuals, unless posted by a Page representing a real brick-and-mortar store, legitimate website, brand or government agency (e.g. police department, fire department). This includes content that:</p>
                                <p>Mentions or depicts firearms, firearm parts, ammunition, explosives, or 3D gun printing files of any firearm or its parts<strong><sup>*</sup></strong>, and</p>
                                <p>Makes an attempt to sell or trade, by which we mean:</p>
                                <p>Explicitly mentioning the product is for sale or trade, or</p>
                                <p>Asking the audience to buy, or</p>
                                <p>Listing the price or noting that the product is free</p>
                                <p>Encouraging contact about the product either by</p>
                                <p>Explicitly asking to be contacted</p>
                                <p>Including any type of contact information</p>
                                <p>Making an attempt to solicit the item for sale, defined as</p>
                                <p>Stating that they are interested in buying the good, or</p>
                                <p>Asking if anyone else has the good for sale/trade</p>
                                <p><strong><sup>*</sup></strong>&nbsp;3D gun printing files or instructions to manufacture firearms using 3D printers or CNC milling machines, including links to websites where such files or instructions are made available, may not be shared by anyone. A Page representing a real brick-and-mortar store, legitimate website or brand can otherwise post content that may promote these items only for sale off of our services and as long as those retailers comply with all applicable laws and regulations.</p>
                                <p>Content that depicts the trade (buying or selling) of human organs and/or blood where trade is defined as:</p>
                                <p>Mentioning or depicting the human organs and/or blood, and</p>
                                <p>Indicating that human organs and/or blood are available for selling or buying, or</p>
                                <p>Listing a price or expressing willingness to discuss price</p>
                                <p>Content that encourages contact to facilitate the trade of human organs and/or blood</p>
                                <p>Content that attempts to sell live animals between private individuals.</p>
                                <p>Content that coordinates or supports the poaching or selling of endangered species and their parts</p>
                                <p>Content that attempts to offer, sell, gift, exchange, or transfer alcohol or tobacco products between private individuals. This includes content that:</p>
                                <p>Mentions or depicts alcohol or tobacco, and</p>
                                <p>Makes an attempt to sell or trade, by which we mean:</p>
                                <p>Explicitly mentions the product is for sale or trade, or</p>
                                <p>Asks the audience to buy, or</p>
                                <p>Lists price or notes product is free</p>
                                <p>And is not posted by a Page representing a real brick-and-mortar store, legitimate website or brand</p>
                                <p>For the following content, we restrict visibility to adults twenty-one years of age and older:</p>
                                <p>Content posted by or promoting a brick-and-mortar store, legitimate website, or brand, which coordinates or promotes the sale or transfer of firearms, firearm parts, ammunition, or explosives. This includes content that</p>
                                <p>Explicitly mentions the product is for sale or transfer and</p>
                                <p>Asks the audience to buy the product, or</p>
                                <p>Lists the price or notes that the product is free, or</p>
                                <p>Encourages contact about the product either by explicitly asking to be contacted or including any type of contact information</p>
                                <p>Content that depicts the sale or attempt to purchase knives, which includes content that</p>
                                <p>Mentions or depicts a knife or bladed weapon, and</p>
                                <p>Explicitly mentions the product is for sale or trade or delivery, or</p>
                                <p>Asks the audience to buy, or</p>
                                <p>Lists the price, or</p>
                                <p>Asks or gives away the product for free between private individuals, or</p>
                                <p>Encourages contact about the product either by:</p>
                                <p>Explicitly asking to be contacted, or</p>
                                <p>Including any type of contact information</p>
                                <p>For the following content, we restrict visibility to adults eighteen years of age and older:</p>
                                <p>Content posted by or promoting a brick and mortar store, legitimate website or brand, which coordinates or promotes the sale or transfer of alcohol or tobacco products off of All You's services. This includes content that:</p>
                                <p>Mentions or depicts alcohol or tobacco, and</p>
                                <p>Explicitly mentions the product is for sale or trade, or</p>
                                <p>Asks the audience to buy, or</p>
                                <p>Lists the price or notes product is free or</p>
                                <p>Encourages contact about the product either by:</p>
                                <p>Explicitly asking to be contacted, or</p>
                                <p>Including any type of contact information</p>
                                <p><strong>Fraud and Deception are not allowed</strong></p>
                                <p>In an effort to prevent and disrupt harmful or fraudulent activity, we remove content aimed at deliberately deceiving people to gain an unfair advantage or deprive another of money, property, or legal right. However, we allow people to raise awareness and educate others as well as condemn these activities using our platform.</p>
                                <p>&nbsp;</p>
                                <p>You are not allowed to post:</p>
                                <p>Content that engages in, promotes, encourages, facilitates, or admits to the following activities:</p>
                                <p>Offering, solicitation and trade of:</p>
                                <p>Goods/property purchased with stolen financial information</p>
                                <p>Future exam papers or answer sheets where answers are not meant to be widely shared</p>
                                <p>Educational and professional certificates</p>
                                <p>Credentials for paid subscription services</p>
                                <p>Fake user reviews</p>
                                <p>Counterfeit currency</p>
                                <p>Offering or selling of personally identifiable information</p>
                                <p>Coordination or facilitation of fixed betting</p>
                                <p>Other forms of deception aimed at depriving people of money or property such as:</p>
                                <p>Confidence schemes, e.g. romance/military impersonation scams</p>
                                <p>Ponzi schemes or Pyramid schemes</p>
                                <p>Setting up false businesses or entities</p>
                                <p>Investment schemes with promise of high rates of return</p>
                                <p>Bribery</p>
                                <p>Embezzlement</p>
                                <p>Money laundering (concealment of the origins of criminally obtained money)</p>
                            </div><!-- End .col-md-6 -->
                        </div><!-- End .row -->
                    </div><!-- End .col-lg-7 -->
                </div><!-- End .row -->
            </div><!-- End .container-fluid -->
        </div><!-- End .about-section -->
</main><!-- End .main -->