<?php
/**
 * Subpages under For Parents and Research.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Guides
{
	/**
	 * @return array<string, array{parent: string, label: string, title: string, lead: string, summary: string, sections: array<int, array<string, mixed>>}>
	 */
	public static function all(): array
	{
		$pages = [];
		foreach (self::definitions() as $slug => $page) {
			$pages[$slug] = $page;
		}

		return $pages;
	}

	/**
	 * @return array<string, array{parent: string, label: string, title: string, lead: string, summary: string, sections: array<int, array<string, mixed>>}>|null
	 */
	public static function find(string $slug): ?array
	{
		$pages = self::all();

		return $pages[$slug] ?? null;
	}

	/**
	 * @return array<int, array{label: string, slug: string, url: string, summary: string}>
	 */
	public static function nav(string $parent): array
	{
		$items = [];
		foreach (self::all() as $slug => $page) {
			if ($page['parent'] !== $parent) {
				continue;
			}
			$items[] = [
				'label' => $page['label'],
				'slug' => $slug,
				'url' => home_url('/' . $parent . '/' . $slug . '/'),
				'summary' => $page['summary'],
			];
		}

		return $items;
	}

	public static function index(string $parent): void
	{
		$items = self::nav($parent);
		if ($items === []) {
			return;
		}
		echo '<nav class="t4l-subindex" aria-label="' . esc_attr($parent === 'research' ? __('Research pages', 'tooth4life') : __('For parents pages', 'tooth4life')) . '">';
		foreach ($items as $item) {
			echo '<a href="' . esc_url($item['url']) . '">';
			echo '<strong>' . esc_html($item['label']) . '</strong>';
			echo '<span>' . esc_html($item['summary']) . '</span>';
			echo '</a>';
		}
		echo '</nav>';
	}

	public static function siblings(string $slug): void
	{
		$page = self::find($slug);
		if ($page === null) {
			return;
		}
		$items = self::nav($page['parent']);
		if (count($items) < 2) {
			return;
		}
		echo '<nav class="t4l-siblings" aria-label="' . esc_attr__('More in this section', 'tooth4life') . '">';
		foreach ($items as $item) {
			if ($item['slug'] === $slug) {
				continue;
			}
			echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['label']) . '</a>';
		}
		echo '</nav>';
	}

	/**
	 * @param array<string, mixed> $section
	 */
	public static function section(array $section): void
	{
		echo '<section class="t4l-doc-block">';
		echo '<div class="t4l-wrap">';
		if (! empty($section['heading'])) {
			echo '<h2>' . esc_html((string) $section['heading']) . '</h2>';
		}
		foreach ((array) ($section['paragraphs'] ?? []) as $paragraph) {
			echo '<p class="t4l-doc-lead">' . esc_html((string) $paragraph) . '</p>';
		}
		$points = (array) ($section['points'] ?? []);
		if ($points !== []) {
			echo '<ul class="t4l-guide-list">';
			foreach ($points as $point) {
				if (is_array($point)) {
					echo '<li><strong>' . esc_html((string) ($point['title'] ?? '')) . '</strong>';
					echo '<span>' . esc_html((string) ($point['text'] ?? '')) . '</span></li>';
				} else {
					echo '<li>' . esc_html((string) $point) . '</li>';
				}
			}
			echo '</ul>';
		}
		echo '</div></section>';
	}

	/**
	 * @return array<string, array{parent: string, label: string, title: string, lead: string, summary: string, sections: array<int, array<string, mixed>>}>
	 */
	private static function definitions(): array
	{
		return [
			'stored-cells' => [
				'parent' => 'for-parents',
				'label' => 'Stored Cells',
				'title' => 'Can stored tooth stem cells still be used?',
				'summary' => 'What laboratory studies show after years in the freezer, and what has not yet been shown in a person.',
				'lead' => 'Laboratory studies have thawed tooth stem cells after long storage and found that they still behave like stem cells. A published study of a person treated with their own baby tooth cells after years in a company bank has not appeared.',
				'sections' => [
					[
						'heading' => 'What the research shows',
						'paragraphs' => [
							'In laboratory work, tooth stem cells frozen for 13 years still behaved like stem cells after thawing. More than 90% of the cells that were checked still carried the markers researchers use to recognise stem cells.',
							'That is evidence about the cells in a dish. It is not evidence of a treatment.',
						],
					],
					[
						'heading' => 'Why researchers keep studying storage',
						'points' => [
							'The cells can be collected without surgery, when a baby tooth falls out or a dentist takes it out.',
							'A person’s own cells are less likely to be attacked by their immune system than cells from a donor.',
							'In a study in dogs, baby tooth stem cells regrew tooth pulp as well as adult tooth stem cells. The researchers suggested that a personal store could be useful later.',
						],
					],
					[
						'heading' => 'What is still unknown',
						'points' => [
							'No published study has yet reported treating a person with their own baby tooth cells after long storage by a company.',
							'Not every tooth gives usable cells. In research laboratories, about 8 in 10 baby teeth did. Back teeth, decayed teeth, and teeth that take too long to reach the laboratory do less well.',
							'How a tooth is collected, packed, and frozen matters. A result from one research laboratory does not describe every service.',
						],
					],
				],
			],
			'storage-to-treatment' => [
				'parent' => 'for-parents',
				'label' => 'Storage to Treatment',
				'title' => 'What would it take to use stored tooth cells?',
				'summary' => 'The steps between a frozen sample and any future treatment. Storage is not a treatment you can book today.',
				'lead' => 'A stored sample is a starting material, not a medicine ready to give. Using it would take several controlled steps, and a proven treatment for that illness would have to exist first.',
				'sections' => [
					[
						'heading' => 'From a stored tooth to a treatment',
						'points' => [
							['title' => 'A treatment has to exist', 'text' => 'A doctor would need an approved treatment, or an approved research study, that uses a patient’s own tooth stem cells for that illness. No such approved treatment exists today.'],
							['title' => 'The cells are thawed and checked', 'text' => 'A sample is thawed and tested to see that the cells are alive, free from germs, and have not changed in a harmful way.'],
							['title' => 'The cells are grown', 'text' => 'Most treatments need many millions of cells, far more than one tooth holds. In the UK, cells grown for a treatment are regulated as a medicine. They have to be made in a licensed facility to medical manufacturing standards, overseen by the MHRA.'],
							['title' => 'They pass quality tests', 'text' => 'The cells have to meet the safety and quality standards set for that particular treatment.'],
							['title' => 'A doctor gives the treatment', 'text' => 'That would happen in a hospital or an approved clinic, with check-ups afterwards.'],
						],
					],
					[
						'heading' => 'What this means for a family',
						'paragraphs' => [
							'Growing enough cells takes weeks, so a stored sample is not something that could help in an emergency.',
							'In the UK, places that store human cells for possible use in patients need a licence from the Human Tissue Authority. Standards for medical-grade cells can also change over the years.',
							'Tooth4Life stores dental stem cells from a milk tooth. Personalised treatment is only a possibility as medical science advances. Storage is not a promise that a therapy is available now.',
						],
					],
				],
			],
			'banking-checklist' => [
				'parent' => 'for-parents',
				'label' => 'Banking Checklist',
				'title' => 'Questions to ask before storing a baby tooth',
				'summary' => 'Questions worth putting in writing before you decide, and claims this service does not make.',
				'lead' => 'If you are comparing storage services, ask these questions in writing. The answers are easier to judge when they are on the page, not only in a conversation.',
				'sections' => [
					[
						'heading' => 'Quality and the laboratory',
						'points' => [
							'Is the place that stores the cells licensed to store them for possible use in patients? What is the licence number?',
							'Which laboratory handles the tooth, and is that the laboratory that would release a sample later?',
							'Are the cells grown before they are frozen, or is the pulp frozen as it arrives? Why is that the method?',
							'Of the teeth received, how many give usable cells? What happens if a tooth does not?',
							'Are cells tested after thawing, and can those results be shown?',
						],
					],
					[
						'heading' => 'Later use, cost, and the contract',
						'points' => [
							'Have any stored samples been used in a study published in a scientific journal?',
							'Are the cells prepared to the standard needed to make a medicine? If not, what extra work would be needed?',
							'Who can ask for the cells to be released, and where can they be sent?',
							'What does storage cost over the years a child is growing up, including yearly fees and any price rises?',
							'What happens to the cells if the company closes, is sold, or the fees stop?',
							'Can the child take responsibility for the sample when they turn 18?',
						],
					],
					[
						'heading' => 'Claims this service does not make',
						'paragraphs' => [
							'Tooth4Life does not list illnesses that stored tooth stem cells treat today. It does not say the cells can treat autism, HIV, or sickle cell disease. It does not call storage an insurance policy, or a family’s only option.',
							'A customer story is not the same as a published study. Storage is a service you can ask about. It is not a treatment you can book today.',
						],
					],
				],
			],
			'stem-cell-science' => [
				'parent' => 'research',
				'label' => 'Stem Cell Science',
				'title' => 'The science of mesenchymal stem cells',
				'summary' => 'Where medicines made from these cells are already approved, and why that does not automatically apply to a tooth.',
				'lead' => 'Medicines made from mesenchymal stem cells exist. They are approved only in certain countries, for a few illnesses, and none of them is made from the cells in a tooth.',
				'sections' => [
					[
						'heading' => 'What these cells can do',
						'paragraphs' => [
							'Mesenchymal stem cells are found in bone marrow, fat, umbilical cord, and the pulp of a tooth. In research they can calm harmful inflammation, send signals that help damaged tissue heal, and turn into tissues such as bone and cartilage.',
							'Scientists think most of the benefit seen in patients comes from the signals and the calming effect, rather than from the cells simply becoming a new tissue.',
						],
					],
					[
						'heading' => 'Medicines already approved',
						'paragraphs' => [
							'Regulators in the United States, China, Japan, India, and South Korea have approved medicines in this family. The strongest evidence is in graft-versus-host disease, which can follow a bone marrow transplant, when the donor’s immune cells attack the patient. In the study behind one US approval, 70% of children with severe disease improved within 28 days.',
							'Other studies have followed knee cartilage for seven years, and poor blood flow in the legs over twelve months. Those products are not made from teeth, and they are not a personal store of one child’s cells.',
						],
					],
					[
						'heading' => 'What this means for a baby tooth',
						'paragraphs' => [
							'Stem cells from a shed baby tooth share important features with these cells, which is why researchers study them. A result from bone marrow or umbilical cord does not automatically transfer to a tooth.',
							'None of the approved medicines uses cells that a family banked from their own child for later.',
						],
					],
				],
			],
			'why-tooth-stem-cells' => [
				'parent' => 'research',
				'label' => 'Why Tooth Stem Cells?',
				'title' => 'Why do researchers study tooth stem cells?',
				'summary' => 'What tooth stem cells do well in the laboratory, and the limits of that evidence.',
				'lead' => 'In the laboratory, stem cells from teeth can grow quickly and give off substances that support nerves. They can also be collected without an operation. Those are real advantages. They have not yet been shown to produce better results in patients.',
				'sections' => [
					[
						'heading' => 'Advantages seen in research',
						'points' => [
							['title' => 'Collected without surgery', 'text' => 'Taking bone marrow is invasive. A baby tooth falls out on its own. A child loses about 20 of them, usually between the ages of 6 and 12, so there is more than one chance to collect.'],
							['title' => 'They grow quickly', 'text' => 'In the study that first described stem cells from shed baby teeth, the cells multiplied faster than bone marrow cells and adult tooth stem cells, and they kept dividing for longer. A later study found the same.'],
							['title' => 'They support nerves', 'text' => 'Tooth stem cells develop from the same early tissue as the nerves of the face. In laboratory comparisons they have given off more nerve-supporting substances than bone marrow or fat cells, and they have helped young nerve cells grow.'],
							['title' => 'They can form vessel-like networks', 'text' => 'In one laboratory comparison, baby tooth stem cells formed blood-vessel-like networks at least as well as bone marrow cells, and better than adult tooth stem cells.'],
							['title' => 'They can calm the immune system', 'text' => 'In laboratory and mouse studies, baby tooth stem cells calmed immune reactions about as well as bone marrow cells.'],
						],
					],
					[
						'heading' => 'Limits to keep in mind',
						'points' => [
							'Stem cells are only about 5% of the cells in tooth pulp, and a tooth holds very little pulp. The cells have to be grown in a laboratory before there are enough for a study, let alone a treatment.',
							'Cells from different teeth, and different children, do not behave in exactly the same way. That makes quality harder to promise.',
							'A decayed or inflamed tooth is less likely to give usable cells. So is a tooth that takes too long to reach the laboratory.',
							'Bone marrow and umbilical cord cells have been tested in many more people. An advantage in the laboratory does not always become a better result in a patient.',
						],
					],
				],
			],
			'studies-in-people' => [
				'parent' => 'research',
				'label' => 'Studies in People',
				'title' => 'What have studies in people found?',
				'summary' => 'Small studies of tooth stem cells in people, mostly for repairing teeth and the bone around them.',
				'lead' => 'Tooth stem cells have given encouraging results in small studies, mostly for repairing teeth and the bone around them. Use for illnesses elsewhere in the body is at a much earlier stage. None of these studies used cells that had spent years in a family bank.',
				'sections' => [
					[
						'heading' => 'How to read the studies',
						'paragraphs' => [
							'Stem cells from baby teeth are called SHED. Stem cells from adult teeth, such as wisdom teeth, are called dental pulp stem cells. Both develop from the same early tissue as the nerves of the face.',
							'A study of fewer than about 100 people, or a study with no comparison group, can hint that something is worth testing. It cannot prove a treatment. Where a patient’s own cells were used, they were collected shortly before the study, not taken out of long-term storage.',
						],
					],
					[
						'heading' => 'What the studies report',
						'points' => [
							['title' => 'Injured teeth in children', 'text' => 'In a comparison of 40 children, teeth treated with their own tooth stem cells grew new pulp, with blood vessels and nerves, within a year. Teeth given the standard treatment did not.'],
							['title' => 'Severe inflammation of the pulp', 'text' => 'In a first test of five people, with no comparison group, scans showed new pulp-like tissue after 24 weeks, and no safety problems were reported.'],
							['title' => 'Bone lost to gum disease', 'text' => 'In a comparison of 29 people, a graft that included tooth stem cells grew more new bone, and firmer gums, than the graft material alone.'],
							['title' => 'A gap in the jaw of children born with a cleft', 'text' => 'In a first test of six children, the bone healed in each child, with no safety problems reported. A larger comparison is still a study to watch, not a result.'],
							['title' => 'Illnesses beyond the mouth', 'text' => 'Early studies have looked at Huntington’s disease, type 2 diabetes, cerebral palsy, and stroke. Some are still running, and some have not published full results. They are not treatments on offer here.'],
						],
					],
				],
			],
			'lessons-from-research' => [
				'parent' => 'research',
				'label' => 'Lessons from Research',
				'title' => 'What early studies can and cannot show',
				'summary' => 'Why a promising early result is not the same as a treatment, including for cells from a tooth.',
				'lead' => 'Several stem cell treatments that looked promising in small studies did not hold up when they were tested properly in larger ones. That is the main reason to be careful about early results, including early results with tooth stem cells.',
				'sections' => [
					[
						'heading' => 'Promising, then disappointing',
						'paragraphs' => [
							'A cell product studied for a complication of Crohn’s disease, in 568 people, worked no better than a dummy treatment. In a knee study of 480 people, no cell injection beat a steroid injection.',
							'Those products were not made from baby teeth. They are a reminder that a good early report can fail the larger test.',
						],
					],
					[
						'heading' => 'Why an early result can mislead',
						'points' => [
							'Without a comparison group, it is hard to know whether people improved because of the cells, because of other care, or because the illness changed on its own.',
							'People who expect to feel better often do, especially after paying for a treatment.',
							'A handful of good results can happen by chance.',
							'Positive studies are more likely to be published, and talked about, than negative ones.',
						],
					],
					[
						'heading' => 'What this means for a baby tooth',
						'paragraphs' => [
							'Most studies of tooth stem cells are still small, or they have no comparison group. Some of those results will hold up. Some will not.',
							'Tooth4Life stores the cells so they are kept. It does not claim that a stored tooth is a treatment today.',
						],
					],
				],
			],
		];
	}
}
