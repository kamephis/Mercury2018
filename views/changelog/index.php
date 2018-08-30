<div class="panel panel-primary">
    <div class="panel-heading">Mercury Changelog</div>
    <div class="panel-body">
        <p>Übersicht über die Änderungen in den verschiedenen Mercury Versionen.</p>
    </div>

    <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
        <thead>
        <tr>
            <th>
                ID
            </th>
            <th>
                Version
            </th>
            <th>
                Kommentar
            </th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td>
                132a386
            </td>
            <td>
                1.0.1.3
            </td>
            <td>
                + Zusatzfeld Kommentar bei Fehlerartikeln hinzugefügt (synchron mit Modal)
            </td>
        </tr>
        <tr>
            <td>
                132a386
            </td>
            <td>
                1.0.1.2
            </td>
            <td>
                + Neuer ItemStatus 6 (für Statistik) eingeführt
            </td>
        </tr>

        <tr>
            <td>
                132a386
            </td>
            <td>
                1.0.1.1
            </td>
            <td>
                + Teamleiter können eine Nachricht für den Kundenservice beim Melden von Artikeln hinterlassen.
            </td>
        </tr>

        <tr>
            <td>
                132a386
            </td>
            <td>
                1.0.1.0
            </td>
            <td>
                + Berechtigung nach Benutzergruppen eingeführt (picker, kus, teamleiter)
            </td>
        </tr>

        <tr>
            <td>
                132a386
            </td>
            <td>
                1.0.9.0
            </td>
            <td>
                + Positionen werden nur noch an einem Lagerplatz gepickt, nicht mehr über alle Lagerplätze hinweg
                + Fehler werden nur noch an einem Lagerplatz erfasst, nicht mehr über alle Lagerplätze hinweg
            </td>
        </tr>
        <tr>
            <td>
                132a386
            </td>
            <td>
                1.0.8.9
            </td>
            <td>
                + Eskalation für Kundenservice
            </td>
        </tr>

        <tr>
            <td>
                132a386
            </td>
            <td>
                1.0.8.8
            </td>
            <td>
                + Anzeiger aller Lagerplätze eines Artikels
            </td>
        </tr>

        <tr>
            <td>
                160bcfd
            </td>
            <td>
                1.0.8.7
            </td>
            <td>
                + Anzeige das RESTSTOFF im Zuschnitt
            </td>
        </tr>

        <tr>
            <td>
                9c1c3ce
            </td>
            <td>
                1.0.8.6
            </td>
            <td>
                + Spinning Icon in der Picklistenerstellung / Pixi Picklistenimport zeigt nun die Aktivität an, solange
                ein Formular versendet wird.
            </td>
        </tr>


        <tr>
            <td>
                c0096e4
            </td>
            <td>
                1.0.8.5
            </td>
            <td>
                + Deaktivieren des Buttons innerhalb der Picklistenerstellung, solange das Formular übermittelt wird.
                Verhindert das mehrmalige Absenden des Formulars.
                <br>
                + Deaktivieren des Buttons innerhalb des Pixi Picklistenimports, solange das Formular übermittelt wird.
                Verhindert das mehrmalige Absenden des Formulars.
            </td>
        </tr>

        <tr>
            <td>
                d0513e1
            </td>
            <td>
                1.0.8.4
            </td>
            <td>
                ~ Message-Handling läuft nun exklusiv über den Main Controller<br>
                ~ Optimierung der Picklistenerstellung (GUI)
            </td>
        </tr>
        <tr>
            <td>
                53d2ea6
            </td>
            <td>
                1.0.8.3
            </td>
            <td>
                ~ Verschieben des Error Message Handlings in den Main Controller
            </td>
        </tr>
        <tr>
            <td>
                90071a8
            </td>
            <td>
                1.0.8.2
            </td>
            <td>
                ~ Bugfix: Sortiertung der Fehlerliste von BinName in BinSortNum geändert
            </td>
        </tr>
        <tr>
            <td>
                e4d56a2
            </td>
            <td>
                1.0.8.1
            </td>
            <td>
                ~ Bugfix: Vergleich Picklistengröße mit aktueller Position korrigiert
            </td>
        </tr>
        <tr>
            <td>
                fa3163a
            </td>
            <td>
                1.0.7.9.1
            </td>
            <td>
                ~ Bezeichnung Fehlbestand in Max. Menge geändert.
            </td>
        </tr>
        <tr>
            <td>
                32bc574
            </td>
            <td>
                1.0.7.9
            </td>
            <td>
                + Neuer Status "fehlerhaft = 4" zur schnellen Identifizierung von Fehlerhaften Positionen -->
                Fehlerhafte Artikel werden in Picklisten und im Backend als bearbeitet betrachtet, Picklisten erreichen
                auch mit fehlerhaften Positionen 100%
            </td>
        </tr>
        <tr>
            <td>
                353ff20
            </td>
            <td>
                1.0.7.8.1
            </td>
            <td>
                ~ Kommentare der Methoden aktualisiert
            </td>
        </tr>
        <tr>
            <td>
                b300078
            </td>
            <td>
                1.0.7.8
            </td>
            <td>
                + Pixi Bestand in die mobile Artikelfehlerliste eingefügt
            </td>
        </tr>
        <tr>
            <td>
                26d1bad
            </td>
            <td>
                1.0.7.7
            </td>
            <td>
                ~ Bugfix - Löschen von Fehlern in der TL Übersicht war nicht möglich (Ajax aktiviert)
            </td>
        </tr>
        <tr>
            <td>
                0c84d04
            </td>
            <td>
                1.0.7.6
            </td>
            <td>
                ~ Bugfix - Beim Etiketttendruck (Zuschnitt) wurde automatisch zur Positionsübersicht gewechselt.
                (saveFehler = 1 anstatt ""
            </td>
        </tr>

        <tr>
            <td>
                c8e0c31
            </td>
            <td>
                1.0.7.5
            </td>
            <td>
                ~ Fehleretikett Zuschnitt erweitert (Max. Anzahl)
            </td>
        </tr>
        <tr>
            <td>
                42de95d
            </td>
            <td>
                1.0.7.4
            </td>
            <td>
                ~ Mobile Fehlerliste (Schnellpick)
            </td>
        </tr>
        <tr>
            <td>
                8dd1046
            </td>
            <td>
                1.0.7.5
            </td>
            <td>
                + Löschen einer Pickliste über das Teamleiter Backend
            </td>
        </tr>
        <tr>
            <td>
                daa6197
            </td>
            <td>
                1.0.7.3
            </td>
            <td>
                ~ Sortierung der Fehlerpickliste geändert
            </td>
        </tr>
        <tr>
            <td>
                1e426f1
            </td>
            <td>
                1.0.7.2
            </td>
            <td>
                ~ Reaktivierung der Pixi Live-Bestandsabfrage in der Fehlerliste (Backend)
            </td>
        </tr>
        <tr>
            <td>
                ec61205
            </td>
            <td>
                1.0.7.1
            </td>
            <td>
                ~ Refresh nach Picken in Fehlerliste ~ Picken in der Fehlerliste via AJAX
            </td>
        </tr>
        <tr>
            <td>
                904901b
            </td>
            <td>
                1.0.7
            </td>
            <td>
                ~ Mercury Teamleiterübersicht überarbeitet - Pixi Daten werden nicht mehr automatisch bei Fehlerartikeln
                abgefragt - Pixi Bestand in der Picklistenübersicht entfernt + Neuer Button "Pixi Daten abfragen" in der
                Fehlerübersicht + Nummerierung der Zeilen in der Fehlerliste + geprüft Status in der Fehlerliste
                hinzugefügt
            </td>
        </tr>

        <tr>
            <td>
                006c04b
            </td>
            <td>
                1.0.6.1
            </td>
            <td>
                ~ picker_model / getPicklistItemCount: Nur Positionen anzeigen, bei denen kein Fehler gemeldet wurde
            </td>
        </tr>
        <tr>
            <td>
                645473d
            </td>
            <td>
                1.0.6
            </td>
            <td>
                ~ %ackfolie% wird nun automatisch der Rollenmaschine zugewiesen
            </td>
        </tr>

        <tr>
            <td>
                9d5deb0
            </td>
            <td>
                1.0.5
            </td>
            <td>
                ~ Session Management erweitert
            </td>
        </tr>

        <tr>
            <td>
                6e3a2b0
            </td>
            <td>
                1.0.4.2
            </td>
            <td>
                ~ Fehlerliste übersichtlicher gestaltet
            </td>
        </tr>

        <tr>
            <td>
                e52361f
            </td>
            <td>
                1.0.4.1
            </td>
            <td>
                ~ Bugfix: Anzeige der Fehlerartikel IS NOT NULL durch != '' ersetzt
            </td>
        </tr>

        <tr>
            <td>
                7c0612e
            </td>
            <td>
                1.0.4
            </td>
            <td>
                ~ Sortierung der Fehlerliste (mobile) nach Laufwegen
            </td>
        </tr>

        <tr>
            <td>
                43d9ab6
            </td>
            <td>
                1.0.3
            </td>
            <td>
                ~ Backend: Anzeige der Zuschneideaufträge on Demand (per Button klick)
            </td>
        </tr>

        <tr>
            <td>
                f9de06e
            </td>
            <td>
                1.0.2
            </td>
            <td>
                ~ Fehlerliste Mobile - Picken integriert
            </td>
        </tr>

        <tr>
            <td>
                7e7aced
            </td>
            <td>
                1.0.1
            </td>
            <td>
                ~ Druckbutton in Pickliste ruft nun die Druckfunktion direkt auf (ohne Modal) ~ Beim Picken eines
                Artikels wird nun der gemeldete Fehler zurückgesetzt
            </td>
        </tr>
        <tr>
            <td>
                9f59e47
            </td>
            <td>
                1.0.0
            </td>
            <td>
                ~ ReImport in Git ~ Anpassungen Views (Fehlerlistendarstellung)
            </td>
        </tr>
        <tr>
            <td>
                c3c6419
            </td>
            <td>
                1.0.0
            </td>
            <td>
                + Mobile App Modus hinzugefügt
            </td>
        </tr>
        <tr>
            <td>
                8b806cf
            </td>
            <td>
                0.9.9
            </td>
            <td>
                + Mobile Druckfunktion (Etikettendruck für Palettenware)
            </td>
        </tr>
        <tr>
            <td>
                81172d6
            </td>
            <td>
                0.9.8
            </td>
            <td>
                ~ Beschleunigung der gesamten Anwendung. Zusammenfassen von Skripten, Optimierung der Pickliste etc.
            </td>
        </tr>
        <tr>
            <td>
                fb37460
            </td>
            <td>
                0.9.7
            </td>
            <td>
                + Picken einer Position innerhalb der Fehlerliste
            </td>
        </tr>
        <tr>
            <td>
                11dd16c
            </td>
            <td>
                0.9.6
            </td>
            <td>
                ~ Picklistenarray optimiert
            </td>
        </tr>
        <tr>
            <td>
                11dd16c
            </td>
            <td>
                0.9.5
            </td>
            <td>
                + Picken einer Position innerhalb der Fehlerliste
            </td>
        </tr>

        <tr>
            <td>
                4f0dcc5
            </td>
            <td>
                0.9.4
            </td>
            <td>
                Anpassung Picklistenanzeige (Mobil): Fehlerhafte Artikel werden nun nicht mehr in der Pickliste
                angezeigt.
            </td>
        </tr>


        <tr>
            <td>
                69004b7
            </td>
            <td>
                0.9.3
            </td>
            <td>
                + Anpassung Fehlerartikel Model / Template (Erweiterung User der Fehler gemeldet hat ~ Login Model -
                Workaround für Mini Barcode Scanner entfernt
            </td>
        </tr>


        <tr>
            <td>
                7b347a3
            </td>
            <td>
                0.9.2
            </td>
            <td>
                + Logout Session destroy verbessert
            </td>
        </tr>


        <tr>
            <td>
                ccf23c9
            </td>
            <td>
                0.9.1
            </td>
            <td>
                + MVC aktualisiert
            </td>
        </tr>


        <tr>
            <td>
                78709a6
            </td>
            <td>
                0.9.0
            </td>
            <td>
                + Prüfen ob eine Session vor dem Login aktiv ist. Falls ja, wird die Session zerstört.
            </td>
        </tr>


        <tr>
            <td>
                6cdb0b7
            </td>
            <td>
                0.8.9
            </td>
            <td>
                + Wiederherstellung
            </td>
        </tr>


        <tr>
            <td>
                222e376
            </td>
            <td>
                0.8.8
            </td>
            <td>
                + Auftrag um Modals erweitert.
            </td>
        </tr>


        <tr>
            <td>
                2d4fe4d
            </td>
            <td>
                0.8.7
            </td>
            <td>
                + Etikettenbearbeitung
            </td>
        </tr>

        <tr>
            <td>
                f19ec32
            </td>
            <td>
                0.8.6
            </td>
            <td>
                + Hilfe MVC Seiten erstellt
            </td>
        </tr>

        <tr>
            <td>
                9aef276
            </td>
            <td>
                0.8.5
            </td>
            <td>
                + Login überarbeitet (für Barcode Scanner optimiert)
            </td>
        </tr>

        <tr>
            <td>
                8505b2d
            </td>
            <td>
                0.8.4
            </td>
            <td>
                + Pixi Model um Funktionen erweitert und in den Auftrag controller eingebunden.
            </td>
        </tr>

        <tr>
            <td>
                73fc5f2
            </td>
            <td>
                0.8.3
            </td>
            <td>
                + Navigation für Test erweitert
            </td>
        </tr>

        <tr>
            <td>
                053cbc8
            </td>
            <td>
                0.8.2
            </td>
            <td>
                + Übergabe der PicklistenNr an die Picklisten View zur Anzeige der zugewiesenen Artikel
            </td>
        </tr>

        <tr>
            <td>
                7b6a7b3
            </td>
            <td>
                0.8.1
            </td>
            <td>
                + Datenbankanbindung komplett + Models (auftrag, picker, picklist) + Anbindung der Modesl mit den Views
                + Live Daten Anzeige in den Views
            </td>
        </tr>

        <tr>
            <td>
                959b58f
            </td>
            <td>
                0.8.0
            </td>
            <td>
                + Top Nav aktualisiert
            </td>
        </tr>

        <tr>
            <td>
                d1c5eed
            </td>
            <td>
                0.7.9
            </td>
            <td>
                + MVC aktualisiert
            </td>
        </tr>

        <tr>
            <td>
                ecf2586
            </td>
            <td>
                0.7.8
            </td>
            <td>
                + Auftrag View
            </td>
        </tr>
        <tr>
            <td>
                27686d1
            </td>
            <td>
                0.7.6
            </td>
            <td>
                + MVC aktualisiert
            </td>
        </tr>
        <tr>
            <td>
                06b0b08
            </td>
            <td>
                0.7.5
            </td>
            <td>
                + Datenbankanbindung (PDO)
            </td>
        </tr>
        <tr>
            <td>
                3933997
            </td>
            <td>
                0.7.4
            </td>
            <td>
                + Session Authentifizierung in die main view ausgelagert.
            </td>
        </tr>
        <tr>
            <td>
                a51513a
            </td>
            <td>
                0.7.3
            </td>
            <td>
                + Erweiterung der Controller + Erweiterung der Views + Erweiterung um Mercury
            </td>
        </tr>
        <tr>
            <td>
                662697d
            </td>
            <td>
                0.7.2
            </td>
            <td>
                + Weiche zwischen PickApp und Auftragsbearbeitung
            </td>
        </tr>
        <tr>
            <td>
                07252cc
            </td>
            <td>
                0.7.1
            </td>
            <td>
                1st GIT commit
            </td>
        </tr>


        </tbody>
    </table>

</div>