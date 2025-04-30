<div class="filter-form">
    <form method="post" action="" class="row align-items-end">
        <div class="col-md-4 mb-2">
            <label for="year" class="form-label">Année</label>
            <select name="year" id="year" class="form-select">
                <option value="all">Toutes les années</option>
                <?php foreach ($years as $yr): ?>
                    <option value="<?= $yr ?>" <?= $yr == $year ? 'selected' : '' ?>>
                        <?= $yr ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <label for="month" class="form-label">Mois</label>
            <select name="month" id="month" class="form-select">
                <option value="all">Tous les mois</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= sprintf('%02d', $i) ?>" <?= sprintf('%02d', $i) == $month ? 'selected' : '' ?>>
                        <?= date_fr('F', mktime(0, 0, 0, $i, 1)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
        </div>
    </form>
</div>