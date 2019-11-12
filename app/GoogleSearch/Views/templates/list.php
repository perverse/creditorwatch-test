<main class="page results">
    <section class="container">
        <div class="row center-xs">
            <div class="col-xs-10 col-sm-8 col-md-6">
                <a href="/" class="logo">
                    <img src="/assets/google-ascii-logo.gif" alt="Image of Google logo in ASCII Art">
                    <div class="sub-text">Results Checker</div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h1>Google Checker results for website "<?= $website ?>"</h1>
            </div>
        </div>
        <div class="row">
            <aside class="col-xs-12 col-sm-5">
                <div class="card">
                    <h2>Statistics</h2>
                    <div class="row stat-row">
                        <div class="col-xs-6">
                            Website
                        </div>
                        <div class="col-xs-6">
                            <?= $website ?>
                        </div>
                    </div>
                    <div class="row stat-row">
                        <div class="col-xs-6">
                            Search term
                        </div>
                        <div class="col-xs-6">
                            <?= $query ?>
                        </div>
                    </div>
                    <div class="row stat-row">
                        <div class="col-xs-6">
                            Appearances / Total results
                        </div>
                        <div class="col-xs-6">
                            <?= $total_mentions ?> / <?= $total_searched ?>
                        </div>
                    </div>
                    <div class="row stat-row">
                        <div class="col-xs-6">
                            Appearance in position(s)
                        </div>
                        <div class="col-xs-6">
                            <?= (count($search_results) ? implode(', ', array_keys($search_results)) : "0") ?>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="col-xs-12 col-sm-7">
                <div class="card">
                    <h2>Results</h2>
                    <?php if (count($search_results)) { ?>
                        <?php foreach ($search_results as $position => $result) { ?>
                            <article class="search-result">
                                <h3>#<?= $position ?> <a href="<?= $result['link'] ?>" target="_blank"><?= $result['title'] ?></a></h3>
                                <p class="url"><a href="<?= $result['link'] ?>" target="_blank" class="green"><?= $result['link'] ?></a></p>
                                <p class="snippet"><?= $result['htmlSnippet'] ?></p>
                            </article>
                        <?php } // end foreach ?>
                    <?php } else { ?>
                        <article class="search-result">
                            <h3>No results found.</h3>
                        </article>
                    <?php } // end if count search results ?>
                </div>
            </div>
        </div>
    </section>
</main>