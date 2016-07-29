<?php

namespace Lex4\FinanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lex4\FinanceBundle\Entity\FinanceSymbols;
use Lex4\FinanceBundle\Form\SymbolType;

class YahooController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->forward('Lex4FinanceBundle:Yahoo:portfolio');
    }

    /**
     * list portfolio
     *
     * @Route("/portfolio", name="yahoo_portfolio")
     * @Method("GET")
     * @Template("Lex4FinanceBundle:Yahoo:index.html.twig")
     */
    public function portfolioAction()
    {
        $entities = $this->getDoctrine()->getManager()
            ->createQueryBuilder()
            ->select('s')
            ->from('Lex4FinanceBundle:FinanceSymbols', 's')
            ->where('s.user = :user')
            ->setParameter('user', $this->getUser())
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
        return [
            'entities' =>  $entities,
        ];
    }

    /**
     * Displays a form to create a new or edit Portfolio entity
     *
     * @Route("/symbol", name="yahoo_symbol")
     * @Method("GET")
     * @Template("Lex4FinanceBundle:Yahoo:index.html.twig")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function symbolAction(Request $request)
    {
        $action = $request->get('id') ? 'edit' : 'new';

        return $this->forward('Lex4FinanceBundle:Yahoo:' . $action, [
            'request'  => $request,
        ]);

    }

    /**
     * view symbol item
     *
     * @Route("/symbol/{id}", name="yahoo_symbol_view")
     * @Method("GET")
     * @Template("Lex4FinanceBundle:Yahoo:symbol_view.html.twig")
     * @param $id
     * @return array
     */
    public function symbolViewAction($id)
    {
        $symbol = $this->getDoctrine()->getRepository('Lex4FinanceBundle:FinanceSymbols')->findOneBy([
            'user' => $this->getUser(),
            'id' => $id,
        ]);
        return [
            'entity' =>  $symbol,
        ];
    }

    /**
     * view create form
     *
     * @Template("Lex4FinanceBundle:Yahoo:symbol_form.html.twig")
     */
    public function newAction()
    {
        return [
            'form'  => $this->createNewForm(new FinanceSymbols())->createView(),
            'title' => 'Add symbol',
        ];
    }

    /**
     * Creates a form to new entity.
     *
     * @param FinanceSymbols $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createNewForm(FinanceSymbols $entity)
    {
        $form = $this->createForm(new SymbolType(), $entity, [
            'action' => $this->generateUrl('yahoo_symbol_create'),
            'method' => 'POST',
        ]);

        return $form;
    }


    /**
     * Creates a new FinanceSymbols entity.
     *
     * @Route("/symbol", name="yahoo_symbol_create")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new FinanceSymbols();
        $form = $this->createNewForm($entity);
        $entity->setUser($this->getUser());
        $form->handleRequest($request);

        if ($status = $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->addFlash('notice', 'Symbol was created!');
            return $this->forward('Lex4FinanceBundle:Yahoo:getData', ['id'=>$entity->getId()]);
        } else {
            return $this->render('Lex4FinanceBundle:Yahoo:symbol_form.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }


    /**
     * view edit form
     *
     * @Template("Lex4FinanceBundle:Yahoo:symbol_form.html.twig")
     * @param Request $request
     * @return array
     */
    public function editAction(Request $request)
    {
        $symbol = $this->getDoctrine()->getManager()->find('Lex4FinanceBundle:FinanceSymbols', $request->get('id'));

        if (!$symbol) {
            throw $this->createNotFoundException('Unable to find FinanceSymbols entity.');
        }

        return [
            'entity'    => $symbol,
            'form'      => $this->createEditForm($symbol)->createView(),
            'title'     => 'Edit symbol',
        ];
    }

    /**
     * Creates a form to edit a FinanceSymbols entity.
     *
     * @param \Lex4\FinanceBundle\Entity\FinanceSymbols $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(FinanceSymbols $entity)
    {
        $form = $this->createForm(new SymbolType(), $entity, [
            'action' => $this->generateUrl('yahoo_symbol_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        return $form;
    }

    /**
     * * Edits an existing FinanceSymbols entity.
     *
     * @Route("/symbol/{id}", name="yahoo_symbol_update")
     * @Method("PUT")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->find('Lex4FinanceBundle:FinanceSymbols', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FinanceSymbols entity.');
        }

        $form = $this->createEditForm($entity);
        $form->handleRequest($request);

        if ($status = $form->isValid()) {
            $em->flush();
            $this->addFlash('notice', 'Symbol was updated!');
            return $this->redirectToRoute('yahoo_portfolio');
        } else {
            return $this->render('Lex4FinanceBundle:Yahoo:symbol_form.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }


    /**
     * Deletes a FinanceSymbols entity.
     *
     * @Route("/symbol/{id}", name="yahoo_symbol_delete")
     * @Method("DELETE")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->find('Lex4FinanceBundle:FinanceSymbols', $id);

        $em->remove($entity);
        $em->flush();

        $this->addFlash('notice', 'Symbol was deleted!');
        return $this->redirectToRoute('yahoo_portfolio');
    }

    /**
     * get symbol data
     *
     * @Route("/get-data/{id}", name="yahoo_data_renew")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getDataAction($id)
    {
        $symbol = $this->getDoctrine()->getRepository('Lex4FinanceBundle:FinanceSymbols')->findOneBy([
            'user' => $this->getUser(),
            'id' => $id,
        ]);
        $data = $this->queryCSV($symbol->getSymbol());
        if ($data != []){
            $data = json_encode($data);
            $symbol->setData($data);
            $em = $this->getDoctrine()->getManager();
            $em->persist($symbol);
            $em->flush();
            $this->addFlash('notice', 'Data were got!');
        } else {
            $this->addFlash('notice', "Warning! Data weren't got!");
        }

        return $this->redirectToRoute('yahoo_symbol_view', ['id'=> $id]);
    }

    /**
     * get data of charts
     *
     * @param $symbol
     * @return array
     */
    private function queryCSV($symbol)
    {
        $date = new \DateTime();
        $endDate = $date->format('Y-m-d');
        $date->modify('-2 year');
        $startDate = $date->format('Y-m-d');
        $groupBy = 'd';

        // set startDate and endDate as option
        // query returns complete available historical data if no date is passed
        if (!empty($startDate)) {
            $startDate = explode('-', $startDate);
            $startDate[1] = $startDate[1]-1; // yahoo index starts with 0 for january

            $configStartDate = '&a=' . $startDate[1] . '&b=' . $startDate[2] . '&c=' . $startDate[0];

        } else {
            $configStartDate = '&a=&b=&c=';
        }
        if (!empty($endDate)) {
            $endDate = explode('-', $endDate);
            $endDate[1] = $endDate[1]-1; // yahoo index starts with 0 for january

            $configEndDate = '&d=' . $endDate[1] . '&e=' . $endDate[2] . '&f=' . $endDate[0];

        } else {
            $configEndDate = '&d=&e=&f=';
        }

        // add start and end date to query url if set
        $configDate = $configStartDate . $configEndDate;

        // set request url
        $baseUrl = 'http://ichart.finance.yahoo.com/table.csv?s=';
        $configValue = '&g=' . $groupBy . '&ignore=.csv';
        $queryUrl = $baseUrl . urlencode($symbol) . $configDate . $configValue;

        // curl request
        $response = $this->curlRequest($queryUrl);

        if (404 == $response['status']) {
            return $data = [];
        }

        // parse csv
        $result = str_getcsv($response['result'], "\n"); //parse rows
        foreach ($result as &$row) { //parse items in row
            $row = str_getcsv($row);
        }
        unset($row);

        // assign headers of first row as key to values of following rows
        $dataKeys = $result[0];
        foreach ($dataKeys as $key => $value) {
            $dataKeys[$key] = str_replace(' ', '', $value); // strip white space
        }
        unset($result[0]);
        $result = array_values($result);

        // build array
        $data = array();
        foreach ($result as $key => $row) {
            foreach ($row as $rowKey => $rowValue) {
                if (in_array($dataKeys[$rowKey], ['Date', 'AdjClose'])){
                    $dataKeys[$rowKey] == 'Date' ? $data[$key][] = strtotime($rowValue) * 1000 : $data[$key][] = $rowValue;
                }
            }
        }
        return $data;
    }

    /**
     * get raw data of charts
     *
     * @param $url
     * @return array
     */
    protected function curlRequest($url)
    {
        $response = array();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response['result'] = curl_exec($ch);
        $response['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response['error'] = curl_error($ch);
        $response['errno'] = curl_errno($ch);
        curl_close($ch);

        return $response;
    }

}
